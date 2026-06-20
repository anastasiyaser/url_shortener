<?php

/**
 * Url fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Url;
use App\Entity\User; // 1. Добавили импорт сущности User
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class UrlFixtures.
 */
class UrlFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        if (
            !$this->manager instanceof ObjectManager
            || !$this->faker instanceof Generator
        ) {
            return;
        }

        // Передаем аргумент int $i в функцию, чтобы разделять ссылки
        $this->createMany(100, 'url', function (int $i): Url {
            $url = new Url();

            $url->setShortCode(
                rtrim(strtr(base64_encode(random_bytes(4)), '+/', 'AZ'), '=')
            );

            $url->setOriginalUrl(
                $this->faker->url()
            );

            $url->setClickCount(
                $this->faker->numberBetween(0, 500)
            );

            $url->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            $url->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            // 2. РАСПРЕДЕЛЕНИЕ: 50% ссылок будут пользовательскими, 50% — гостевыми
            if ($i % 2 === 0) {
                // Ссылка зарегистрированного пользователя
                /** @var User $user */
                $user = $this->getRandomReference('user', User::class);
                $url->setUser($user);

                // 🚀 МАГИЯ: Автоматически записываем email этого пользователя в поле ссылки!
                $url->setGuestEmail($user->getEmail());
            } else {
                // Ссылка анонимного гостя
                $url->setGuestEmail($this->faker->email());
                $url->setUser(null); // У гостя связи с User нет
            }

            $tags = $this->getRandomReferenceList(
                'tag',
                Tag::class,
                rand(1, 3)
            );

            /** @var Tag $tag */
            foreach ($tags as $tag) {
                $url->addTag($tag);
            }

            return $url;
        });
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[]
     *
     * @psalm-return array{0: TagFixtures::class, 1: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        // 3. Добавили UserFixtures, чтобы пользователи создавались в базе раньше, чем ссылки
        return [TagFixtures::class, UserFixtures::class];
    }
}
