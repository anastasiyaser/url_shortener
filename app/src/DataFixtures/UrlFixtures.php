<?php

namespace App\DataFixtures;

use App\Entity\Url;

class UrlFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $url = new Url();
            $url->setShortCode($this->faker->unique()->ean8());
            $url->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
            $url->setUpdatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
            $this->manager->persist($url);
        }
        $this->manager->flush();
    }
}
