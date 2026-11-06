<?php namespace System\Core;

class Spreadsheet
{
    public static function create(array $rows)
    {
        if( !class_exists('\ZipArchive') || !class_exists('\XMLWriter') )
            throw new \RuntimeException('Se requieren las extensiones ZIP y XMLWriter de PHP');

        $file = tempnam(sys_get_temp_dir(), 'consolidado_');
        if( $file === false )
            throw new \RuntimeException('No se pudo crear el archivo temporal');

        $zip = new \ZipArchive;
        if( $zip->open($file, \ZipArchive::OVERWRITE) !== true )
        {
            unlink($file);
            throw new \RuntimeException('No se pudo abrir el archivo Excel');
        }

        try
        {
            $files = [
                '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>',
                '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
                'xl/workbook.xml' => '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Entradas" sheetId="1" r:id="rId1"/></sheets></workbook>',
                'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>',
                'xl/worksheets/sheet1.xml' => self::worksheet($rows),
            ];
            foreach( $files as $name => $content )
            {
                if( !$zip->addFromString($name, $content) )
                    throw new \RuntimeException('No se pudo escribir el archivo Excel');
            }
            if( !$zip->close() )
                throw new \RuntimeException('No se pudo finalizar el archivo Excel');
        }
        catch(\Throwable $e)
        {
            $zip->close();
            if( file_exists($file) && !unlink($file) )
                error_log('No se pudo eliminar el archivo temporal de Excel');
            throw new \RuntimeException('No se pudo generar el archivo Excel', 0, $e);
        }

        return $file;
    }

    private static function worksheet(array $rows)
    {
        $xml = new \XMLWriter;
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('worksheet');
        $xml->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $xml->startElement('sheetData');
        foreach( $rows as $index => $row )
        {
            $xml->startElement('row');
            $xml->writeAttribute('r', (string) ($index + 1));
            foreach( array_values($row) as $column => $value )
            {
                $text = (string) $value;
                if( !mb_check_encoding($text, 'UTF-8') )
                    throw new \RuntimeException('Hay datos que no están codificados en UTF-8');
                $text = preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $text);
                $xml->startElement('c');
                $xml->writeAttribute('r', self::column($column + 1) . ($index + 1));
                $xml->writeAttribute('t', 'inlineStr');
                $xml->startElement('is');
                $xml->startElement('t');
                $xml->writeAttribute('xml:space', 'preserve');
                $xml->text($text);
                $xml->endElement();
                $xml->endElement();
                $xml->endElement();
            }
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();
        return $xml->outputMemory();
    }

    private static function column($number)
    {
        $name = '';
        while( $number > 0 )
        {
            $number--;
            $name = chr(65 + $number % 26) . $name;
            $number = intdiv($number, 26);
        }
        return $name;
    }
}
