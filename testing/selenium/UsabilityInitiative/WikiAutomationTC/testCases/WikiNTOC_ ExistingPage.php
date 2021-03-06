<?php
require_once 'WikiNTOC_TC.php';
/**
 * This test case will be handling the NTOC related functions.
 * Adding different header levels via tool bar and verify the output
 * Date : Apr - 2010
 * @author : BhagyaG - Calcey
 */
class WikiNTOC_ExistingPage extends WikiNTOC_TC{

    // Set up the testing environment
    function setup(){
       parent::setUp();
    }

    //Open a random page
    function openRandomPage(){
       parent::doOpenLink();
       parent::doLogin();
       parent::doAccessRandomPage();
       parent::doEditPage();
    }

    // Add header level 2 and verify Header output
    function testHeaderLevel2(){
       $this->openRandomPage();
       parent::verifyHeaderLevel2();
       parent::doLogout();
    }

      // Add header level 3 and verify Header output
    function testHeaderLevel3(){
       $this->openRandomPage();;
       parent::verifyHeaderLevel3();
       parent::doLogout();
    }

     // Add header level 4 and verify Header output
    function testHeaderLevel4(){
       $this->openRandomPage();
       parent::verifyHeaderLevel4();
       parent::doLogout();
    }

     // Add header level 5 and verify Header output
    function testHeaderLevel5(){
       $this->openRandomPage();
       parent::verifyHeaderLevel5();
       parent::doLogout();
    }

     // Add header level 2 & 3 and verify Header output
    function testHeaderLevel2and3(){
       $this->openRandomPage();
       parent::verifyHeaderLevel2and3();
       parent::doLogout();
    }

    // Add header level 2 , 3 & 4 and verify Header output
    function testHeaderLevel23and4(){
       $this->openRandomPage();;
       parent::verifyHeaderLevel23and4();
       parent::doLogout();
    }

    // Add header level 2 , 3 , 4 & 5 and verify Header output
    function testHeaderLevel234and5(){
       $this->openRandomPage();
       parent::verifyHeaderLevel234and5();
       parent::doLogout();
    }

}
?>
