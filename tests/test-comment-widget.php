<?php

class testCommentWidget extends WP_UnitTestCase {

    function testAuthorSQL() {
        $pmc = new \PMC\Widget\pmcCommentWidget();
        $this->assertArrayHasKey( 'data', $pmc->getFakeForTest() );
    }
}

