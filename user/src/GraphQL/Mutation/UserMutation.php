<?php

namespace App\GraphQL\Mutation;

use App\Amqp\Message\User\CreateUserMessage;
use App\Amqp\Message\User\DeleteUserMessage;
use App\Amqp\Message\User\UpdatePasswordMessage;
use App\Amqp\Message\User\UpdateUserMessage;
use App\Business\UserBusiness;
use App\Entity\User;
use App\Entity\UserRole;
use App\Form\Type\CreateUserType;
use App\Form\Type\UpdateUserType;
use App\Form\Type\User\UpdatePasswordType;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserMutation implements MutationInterface
{
    public function __construct(
        private readonly MessageBusInterface  $bus,
        private readonly UserBusiness         $userBusiness,
        private readonly FormFactoryInterface $formFactory
    )
    {

    }

    public function createUser(array $input): bool
    {
        $user = new User();
        $this->valid(CreateUserType::class, $user, $input);

        $this->bus->dispatch(
            (new CreateUserMessage())
                ->setEmail($user->getEmail())
                ->setFirstname($user->getFirstname())
                ->setLastname($user->getLastname())
                ->setPlainPassword($user->getPlainPassword())
                ->setRoles([UserRole::USER])
        );

        return true;
    }

    public function updateUser(array $input): bool
    {
        $user = new User();
        $this->valid(UpdateUserType::class, $user, $input);

        $this->bus->dispatch(
            (new UpdateUserMessage())
                ->setId($this->userBusiness->getCurrentUser()->getId())
                ->setFirstname($user->getFirstname())
                ->setLastname($user->getLastname())
        );

        return true;
    }

    public function updatePassword(array $input): bool
    {
        $user = new User();
        $this->valid(UpdatePasswordType::class, $user, $input);

        $this->bus->dispatch(
            (new UpdatePasswordMessage())
                ->setId($this->userBusiness->getCurrentUser()->getId())
                ->setPlainPassword($user->getPlainPassword())
        );

        return true;
    }

    public function deleteUser(int $id): bool
    {
        $this->bus->dispatch(
            (new DeleteUserMessage())
                ->setId($id)
        );

        return true;
    }

    protected function valid(string $formType, object $entity, array $data): void
    {
        $form = $this->formFactory->create($formType, $entity)->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $message = [];
            foreach ($form->getErrors(true) as $error) {
                $message[] = $error->getOrigin()->getName() . ' : ' . $error->getMessage();
            }

            throw new \InvalidArgumentException(implode(' ', $message));
        }
    }
}