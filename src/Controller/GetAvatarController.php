<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Storage\StorageInterface;

class GetAvatarController extends AbstractController
{
    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(User $data): Response
    {
        $mediaObj = $data->getAvatar();

        try {
            if (null !== $mediaObj) {
                $avatarPath = $this->storage->resolvePath($mediaObj, 'file');
                $avatar = file_get_contents($avatarPath);

                return new Response($avatar, 200, ['Content-Type' => 'image/png']);
            }
        } catch (\Exception $e) {
            // ignore
        }

        return new Response(file_get_contents('assets/images/avatars/default-avatar.png'), 200);
    }
}
