<?php
/**
 * Short description
 *
 * PHP version 7.2
 *
 * @category
 * @package
 * @author Christophe PERROTIN
 * @copyright 2018
 * @license MIT License
 * @link http://wwww.perrotin.eu
 */

// src/Service/FileUploader.php
namespace App\Services;

use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     *
     * @param Image $image
     * @return Image $image
     */
    public function saveImage(Picture $image): Picture
    {
        // Récupère le fichier de l'image uploadée
        $file = $image->getFile();
        // Créer un nom unique pour le fichier
        $name = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
        $image->setName($name);
        // Déplace le fichier
        $path = 'uploads';
        $file->move($path, $name);

        return $image;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
