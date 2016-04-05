<?php
namespace Zero\Base\Model;

use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Model\Exception as FrameworkException;
use Magento\Framework\File\Uploader;
use Magento\Framework\App\Filesystem\DirectoryList;

class Upload
{
    /**
     * uploader factory
     *
     * @var \Magento\Core\Model\File\UploaderFactory
     */
    protected $uploaderFactory;
    protected $_directory;
    protected $_imageFactory;

    /**
     * constructor
     *
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory
    )
    {
        $this->uploaderFactory = $uploaderFactory;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_imageFactory = $imageFactory;

    }
    /**
     * upload file
     *
     * @param $input
     * @param $destinationFolder
     * @param $data
     * @return string
     * @throws \Magento\Framework\Model\Exception
     */
    public function uploadFileAndGetName($destinationFolder, $data, $fieldName = 'image',$type)
    {
        try {
            if (isset($data[$fieldName]['delete'])) {
                return '';
            } else {
                $uploader = $this->uploaderFactory->create(['fileId' => $fieldName]);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);
                $result = $uploader->save($destinationFolder);
                // create thumbnail path
                // $thumbPath = $destinationFolder . '/thumbnails';
                // $this->resizeFile($destinationFolder . '/' . $uploader->getUploadedFileName(), $thumbPath, 380, 200, true);
                // create thumbnail
                if($type == 'image') {
                    $thumbPath = $destinationFolder . '/thumbnails';
                    $this->resizeFile($destinationFolder . '/' . $uploader->getUploadedFileName(), $thumbPath, 380, 200, true);
                }
                return $result['file'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                var_dump($e->getMessage());die;
                throw new FrameworkException($e->getMessage());
            } else {
                if (isset($data[$fieldName]['value'])) {
                    return $data[$fieldName]['value'];
                }
            }
        }
        return '';
    }

    /**
     * Create thumbnail for image and save it to thumbnails directory
     *
     * @param string $source Image path to be resized
     * @param bool $keepRation Keep aspect ratio or not
     * @return bool|string Resized filepath or false if errors were occurred
     */
    public function resizeFile($source, $targetDir, $widthResize, $heightResize, $keepRation = true)
    {

        $realPath = $this->_directory->getRelativePath($source);
        if (!$this->_directory->isFile($realPath) || !$this->_directory->isExist($realPath)) {
            return false;
        }

        $pathTargetDir = $this->_directory->getRelativePath($targetDir);
        if (!$this->_directory->isExist($pathTargetDir)) {
            $this->_directory->create($pathTargetDir);
        }
        if (!$this->_directory->isExist($pathTargetDir)) {
            return false;
        }
        $image = $this->_imageFactory->create();
        $image->open($source);
        $image->keepAspectRatio($keepRation);
        $originalWidth = $image->getOriginalWidth();
        $originalHeight = $image->getOriginalHeight();
        if ($originalWidth > $originalHeight) {
            $image->resize(null, $heightResize);
            $space = ($image->getOriginalWidth() - $widthResize) / 2;
            $image->crop(0, $space, $space, 0);
        }
        else {
            $image->resize($widthResize, null);
            $space = ($image->getOriginalHeight() - $heightResize) / 2;
            $image->crop($space, 0, 0, $space);
        }

        $dest = $targetDir . '/' . pathinfo($source, PATHINFO_BASENAME);
        $image->save($dest);
        if ($this->_directory->isFile($this->_directory->getRelativePath($dest))) {
            return $dest;
        }
        return false;
    }
}
