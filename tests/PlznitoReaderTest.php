<?php

include ROOT_PROJECT."PlznitoReader.php";

use PHPUnit\Framework\TestCase;
use JasnaPaka\Plznito\PlznitoReader;

class PlznitoReaderTest extends TestCase {

    public function testLoad() {
        $reader = new PlznitoReader(TEST_DATA_DIR."list.json");
        $this->assertEquals(354, $reader->getCount());
    }

}
