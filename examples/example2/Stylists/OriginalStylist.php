<?php

use Dframe\FileStorage\Stylist;
use Imagecraft\ImageBuilder;

/*
 * Prosty stylista
 * Zwraca obrazek taki jakim jest
 */

/**
 * Class OriginalStylist
 */
class OriginalStylist extends Stylist
{
    /**
     * @param resource $originStream
     * @param string   $extension
     * @param bool     $stylistObj
     * @param bool     $stylistParam
     *
     * @return bool|resource
     * @throws Exception
     */
    public function stylize($originStream, $extension, $stylistObj = false, $stylistParam = false)
    {
        $options = ['engine' => 'php_gd', 'locale' => 'pl_PL'];
        $builder = new ImageBuilder($options);

        $layer = $builder->addBackgroundLayer();
        $contents = stream_get_contents($originStream);
        $layer->contents($contents);

        fclose($originStream);

        $image = $builder->save();

        $tmpFile = tmpfile();
        if ($image->isValid()) {
            fwrite($tmpFile, $image->getContents());
        } else {
            throw new Exception($image->getMessage()); //echo $image->getMessage().PHP_EOL;
        }

        rewind($tmpFile);
        return $tmpFile;
    }

    /**
     * @param $stylistParam
     *
     * @return string
     */
    public function identify($stylistParam)
    {
        return 'originalStylist';
    }
}
