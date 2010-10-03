<?php

require_once dirname( __FILE__ ) . '/ApiSetup.php';

class ApiWatchTest extends ApiTestSetup {

	function setUp() {
		parent::setUp();
	}

	function testLogin() {
		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => self::$user->userName,
			'lgpassword' => self::$user->password ) );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "NeedToken", $data[0]['login']['result'] );
		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest( array(
			'action' => 'login',
			"lgtoken" => $token,
			"lgname" => self::$user->userName,
			"lgpassword" => self::$user->password ), $data );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "Success", $data[0]['login']['result'] );
		$this->assertArrayHasKey( 'lgtoken', $data[0]['login'] );
	}

	function testGetToken() {

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'titles' => 'Main Page',
			'intoken' => 'edit|delete|protect|move|block|unblock',
			'prop' => 'info' ) );

		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'pages', $data[0]['query'] );
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );

		$this->assertArrayHasKey( $key, $data[0]['query']['pages'] );
		$this->assertArrayHasKey( 'edittoken', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'movetoken', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'deletetoken', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'blocktoken', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'unblocktoken', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'protecttoken', $data[0]['query']['pages'][$key] );
	}

	/**
	 * @depends testGetToken
	 */
	function testWatchEdit( $data ) {
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];

		$data = $this->doApiRequest( array(
			'action' => 'edit',
			'title' => 'Main Page',
			'text' => 'new text',
			'token' => $pageinfo['edittoken'],
			'watchlist' => 'watch' ), $data );
		$this->assertArrayHasKey( 'edit', $data[0] );
		$this->assertArrayHasKey( 'result', $data[0]['edit'] );
		$this->assertEquals( 'Success', $data[0]['edit']['result'] );
	}

	/**
	 * @depends testWatchEdit
	 */
	function testWatchClear( $data ) {
		$data = $this->doApiRequest( array(
			'action' => 'query',
			'list' => 'watchlist' ), $data );

		if ( isset( $data[0]['query']['watchlist'] ) ) {
			$wl = $data[0]['query']['watchlist'];

			foreach ( $wl as $page ) {
				$data = $this->doApiRequest( array(
					'action' => 'watch',
					'title' => $page['title'],
					'unwatch' => true ), $data );
			}
		}
		$data = $this->doApiRequest( array(
			'action' => 'query',
			'list' => 'watchlist' ), $data );
		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'watchlist', $data[0]['query'] );
		$this->assertEquals( 0, count( $data[0]['query']['watchlist'] ) );
	}

	/**
	 * @depends testGetToken
	 */
	function testWatchProtect( $data ) {
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];

		$data = $this->doApiRequest( array(
			'action' => 'protect',
			'token' => $pageinfo['protecttoken'],
			'title' => 'Main Page',
			'protections' => 'edit=sysop',
			'watchlist' => 'unwatch' ), $data );

		$this->assertArrayHasKey( 'protect', $data[0] );
		$this->assertArrayHasKey( 'protections', $data[0]['protect'] );
		$this->assertEquals( 1, count( $data[0]['protect']['protections'] ) );
		$this->assertArrayHasKey( 'edit', $data[0]['protect']['protections'][0] );
	}

	/**
	 * @depends testGetToken
	 */
	function testGetRollbackToken( $data ) {
		$data = $this->doApiRequest( array(
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => 'Main Page',
			'rvtoken' => 'rollback' ), $data );

		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'pages', $data[0]['query'] );
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );

		$this->assertArrayHasKey( 'pageid', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'revisions', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 0, $data[0]['query']['pages'][$key]['revisions'] );
		$this->assertArrayHasKey( 'rollbacktoken', $data[0]['query']['pages'][$key]['revisions'][0] );
	}

	/**
	 * @depends testGetRollbackToken
	 */
	function testWatchRollback( $data ) {
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key]['revisions'][0];

		try {
			$data = $this->doApiRequest( array(
				'action' => 'rollback',
				'title' => 'Main Page',
				'user' => self::$user->userName,
				'token' => $pageinfo['rollbacktoken'],
				'watchlist' => 'watch' ), $data );
		} catch( UsageException $ue ) {
			if( $ue->getCodeString() == 'onlyauthor' ) {
				$this->markTestIncomplete( "Only one author to 'Main Page', cannot test rollback" );
			}
		}
		$this->assertArrayHasKey( 'rollback', $data[0] );
		$this->assertArrayHasKey( 'title', $data[0]['rollback'] );
	}

	/**
	 * @depends testGetToken
	 */
	function testWatchDelete( $data ) {
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];

		$data = $this->doApiRequest( array(
			'action' => 'delete',
			'token' => $pageinfo['deletetoken'],
			'title' => 'Main Page' ), $data );
		$this->assertArrayHasKey( 'delete', $data[0] );
		$this->assertArrayHasKey( 'title', $data[0]['delete'] );

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'list' => 'watchlist' ), $data );

	    $this->markTestIncomplete( 'This test needs to verify the deleted article was added to the users watchlist' );
	}

}
