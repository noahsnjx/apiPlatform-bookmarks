<?php

namespace App\DataFixtures;

use App\Factory\BookmarkFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookmarkFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $bookmarks = json_decode(file_get_contents(__DIR__.'/data/bookmarks.json'), true);
        BookmarkFactory::createSequence($bookmarks);
    }
}
