<?php

namespace App\Controller;

namespace App\Controller;

use App\Entity\MediaObject;
use App\Entity\User;
use App\Repository\MediaObjectRepository;
use App\Repository\UserRepository;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;

#[AsController]
final class UploadNewAvatarAction extends AbstractController
{
    private Security $security;
    private Imagine $imagine;

    public function __construct(Security $security, Imagine $imagine)
    {
        $this->security = $security;
        $this->imagine = $imagine;
    }

    public function __invoke(Request $request, MediaObjectRepository $mediaObjectRepository, UserRepository $userRepository): MediaObject
    {
        $user = $this->security->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedException('Invalid user');
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if (UPLOAD_ERR_OK != $uploadedFile->getError()) {
            throw new UploadException("File upload error: {$uploadedFile->getError()} ({$uploadedFile->getErrorMessage()})");
        }

        $tempFilePath = $uploadedFile->getRealPath();

        /* TODO: remove old avatar
        $oldAvatar = $user->getAvatar();
        if (null !== $oldAvatar) {
            $mediaObjectRepository->remove($oldAvatar);
        } */

        /* redimensionnement */

        $tempFilePath = $uploadedFile->getRealPath();
        $image = $this->imagine->open($tempFilePath);
        $image->resize(new Box(30, 30));
        $image->save($tempFilePath.'.png', [
            'format' => 'png',
        ]);

        $mediaObject = new MediaObject();
        $mediaObject->file = $uploadedFile;

        $mediaObjectRepository->save($mediaObject, true);
        // $user->setAvatar($mediaObject);
        $userRepository->updateAvatar($user, $mediaObject);

        return $mediaObject;
    }
}
