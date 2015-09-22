<?php

class testCommentWidget extends WP_UnitTestCase {

	function testSample() {
		$this->assertTrue( true );
	}

    function testAuthorSQL() {
        $pmc = new \PMC\Widget\pmcCommentWidget();
        $this->assertArrayHasKey( 'data', $pmc->getFakeForTest() );
    }
}

