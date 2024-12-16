<?php
namespace Tests\Support;

use Tests\Support\AcceptanceTester;

class BrowseMediaCest
{
    public function _before(AcceptanceTester $I)
    {
        // Any setup before each test
    }

    public function testPageLoads(AcceptanceTester $I)
    {
        $this->loginAndNavigateToBrowseMedia($I);
        $I->see('Browse Media', 'h1');
        $I->seeElement('.media-grid');
    }

    public function testMediaCardsDisplay(AcceptanceTester $I)
    {
        $this->loginAndNavigateToBrowseMedia($I);
        $I->seeElement('.media-card');
        $I->click('.details-toggle');
        $I->seeElement('.media-card-details');
    }

    public function testSearchFunctionality(AcceptanceTester $I)
    {
        $this->loginAndNavigateToBrowseMedia($I);
        $I->fillField('input[name=query]', 'Sample Media');
        $I->click('button[type=submit]');
        $I->see('Search Results');
    }

    public function testBranchSelectionUpdatesAvailability(AcceptanceTester $I)
    {
        $this->loginAndNavigateToBrowseMedia($I);
        $I->click('.details-toggle');
        $I->selectOption('.branch-select', 'Central Library');
        $I->see('Copies available: ', '.quantity');
    }

    public function testBorrowMedia(AcceptanceTester $I)
    {
        $this->loginAndNavigateToBrowseMedia($I);
        $I->click('.details-toggle');
        $I->selectOption('.branch-select', 'Central Library');
        $I->click('.borrow-btn');
    }

    public function testAddToWishlist(AcceptanceTester $I)
    {
        $this->loginAndNavigateToBrowseMedia($I);
        $I->click('.details-toggle');
        $I->selectOption('.branch-select', 'Central Library');

        // Check availability and interact with the wishlist button
        $availabilityText = $I->grabTextFrom('.quantity');
        if (strpos($availabilityText, '0') !== false) {
            $I->seeElement('.wishlist-btn');
            $I->click('.wishlist-btn');
            $I->see('The media has been added to your wishlist');
        } else {
            $I->comment('Wishlist button not available because copies are in stock.');
        }
    }

    public function testDeliveryRequest(AcceptanceTester $I)
    {
        $this->loginAndNavigateToBrowseMedia($I);
        $I->click('.details-toggle');
        $I->fillField('input[name=address]', '123 Main Street');
        $I->fillField('input[name=delivery_date]', date('Y-m-d', strtotime('+1 day')));
        $I->click('.delivery-btn');
    }

    private function loginAndNavigateToBrowseMedia(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Login');
        $I->fillField('email', '123@123.com');
        $I->fillField('password', '123456');
        $I->click('button[type=submit]');
        $I->see('Browse Media');
        $I->click('Browse Media');
    }
}
