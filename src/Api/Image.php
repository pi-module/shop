<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('image', 'shop')->rename($image, $prefix, $path);
 * Pi::api('image', 'shop')->process($image, $path);
 */

class Image extends AbstractApi
{
    public function rename($image = '', $prefix = 'image_', $path = '')
    {
        $config = Pi::service('registry')->config->read($this->getModule(), 'image');

        // Check image name
        if (empty($image)) {
            return $prefix . '%random%';
        }
        // Separating image name and extension
        $name      = pathinfo($image, PATHINFO_FILENAME);
        $extension = pathinfo($image, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        // strip name
        $name = _strip($name);
        $name = strtolower(trim($name));
        $name = preg_replace("/[^a-zA-Z0-9 ]+/", "", $name);
        $name = array_filter(explode(' ', $name));
        $name = implode('-', $name) . '.' . $extension;
        // Check text length
        if (mb_strlen($name, 'UTF-8') < 8) {
            $name = $prefix . '%random%';
        }
        // Set original path
        $original = Pi::path(
            sprintf('upload/%s/original/%s/%s', $config['image_path'], $path, $name)
        );
        // Check file exist
        if (Pi::service('file')->exists($original)) {
            return $prefix . '%random%';
        }
        // return
        return $name;
    }

    public function process($image, $path)
    {
        $config = Pi::service('registry')->config->read($this->getModule(), 'image');

        // Set original path
        $original = Pi::path(
            sprintf('upload/%s/original/%s/%s', $config['image_path'], $path, $image)
        );

        // Set large path
        $large = Pi::path(
            sprintf('upload/%s/large/%s/%s', $config['image_path'], $path, $image)
        );

        // Set medium path
        $medium = Pi::path(
            sprintf('upload/%s/medium/%s/%s', $config['image_path'], $path, $image)
        );

        // Set thumb path
        $thumb = Pi::path(
            sprintf('upload/%s/thumb/%s/%s', $config['image_path'], $path, $image)
        );

        // Set options
        $options = [
            'quality' => empty($config['image_quality']) ? 75 : $config['image_quality'],
        ];

        // Get image size
        $size = getimagesize($original);

        // Resize to large
        if ($size[0] > $config['image_largew'] && $size[1] > $config['image_largeh']) {
            Pi::service('image')->resize(
                $original,
                [$config['image_largew'], $config['image_largeh'], true],
                $large,
                '',
                $options
            );
        } else {
            Pi::service('file')->copy($original, $large, trye);
        }

        // Resize to medium
        if ($size[0] > $config['image_mediumw'] && $size[1] > $config['image_mediumh']) {
            Pi::service('image')->resize(
                $original,
                [$config['image_mediumw'], $config['image_mediumh'], true],
                $medium,
                '',
                $options
            );
        } else {
            Pi::service('file')->copy($original, $medium, trye);
        }

        // Resize to thumb
        if ($size[0] > $config['image_thumbw'] && $size[1] > $config['image_thumbh']) {
            Pi::service('image')->resize(
                $original,
                [$config['image_thumbw'], $config['image_thumbh'], true],
                $thumb,
                '',
                $options
            );
        } else {
            Pi::service('file')->copy($original, $thumb, trye);
        }

        // Watermark
        if ($config['image_watermark']) {
            // Set watermark image
            $watermarkImage = (empty($config['image_watermark_source'])) ? '' : Pi::path($config['image_watermark_source']);
            if (empty($watermarkImage) || !file_exists($watermarkImage)) {
                $logoFile       = Pi::service('asset')->logo();
                $watermarkImage = Pi::path($logoFile);
            }

            // Watermark large
            Pi::service('image')->watermark(
                $large,
                '',
                $watermarkImage,
                $config['image_watermark_position']
            );

            // Watermark medium
            Pi::service('image')->watermark(
                $medium,
                '',
                $watermarkImage,
                $config['image_watermark_position']
            );

            // Watermark thumb
            Pi::service('image')->watermark(
                $thumb,
                '',
                $watermarkImage,
                $config['image_watermark_position']
            );
        }
    }
}
