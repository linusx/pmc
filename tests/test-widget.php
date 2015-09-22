<?php

class testWidget extends WP_UnitTestCase {

    function testAuthorSQL() {
        $pmc = new \PMC\Widget\pmcWidget();
        $this->assertArrayHasKey( 'data', $pmc->getFakeForTest() );
    }
}

