<?php

class testTaxonomyWidget extends WP_UnitTestCase {

    function testAuthorSQL() {
        $pmc = new \PMC\Widget\pmcTaxonomyWidget();
        $this->assertArrayHasKey( 'data', $pmc->getFakeForTest() );
    }
}

