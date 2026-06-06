<?php

/**
 * Url fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Url;
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

        $this->createMany(100, 'url', function (): Url {
            $url = new Url();

            $url->setShortCode(
                $this->faker->unique()->ean8()
            );

            $url->setOriginalUrl(
                $this->faker->url()
            );

            $url->setGuestEmail(
                $this->faker->email()
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
     * @psalm-return array{0: TagFixtures::class}
     */
    public function getDependencies(): array
    {
        return [TagFixtures::class];
    }
}
