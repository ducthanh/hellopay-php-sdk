<?php
/**
 * Copyright 2015 HELLOPAY SINGAPORE PTE. LTD.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * helloPay.
 *
 * As with any software that integrates with the helloPay platform, your use
 * of this software is subject to the helloPay Developer Principles and
 * Policies [https://www.hellopay.com.sg/privacy-policy.html]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */

namespace HelloPay\Tests;

use HelloPay\HelloPay;
use HelloPay\HelloPayResponse;
use Mockery as m;

class HelloPayProxy extends HelloPay
{
    public function __construct()
    {
        $httpClientMock = m::mock('HelloPay\HttpClients\HelloPayCurlHttpClient');
        $this->httpClient = $httpClientMock;
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }
}

/**
 * Class HelloPayTest
 *
 * @package HelloPay
 */
class HelloPayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test constructor error without passed shopConfig param
     *
     * @expectedException \HelloPay\Exceptions\HelloPaySDKException
     * @expectedExceptionMessage Required "shopConfig" key not supplied in config and could not find fallback environment variable "HELLOPAY_SHOP_CONFIG"
     */
    public function testConstructionMissingShopConfig()
    {
        new HelloPay([]);
    }

    /**
     * Test constructor error without passed apiUrl param
     *
     * @expectedException \HelloPay\Exceptions\HelloPaySDKException
     * @expectedExceptionMessage Required "apiUrl" key not supplied in config and could not find fallback environment variable "HELLOPAY_API_URL"
     */
    public function testConstructionMissingApiUrl()
    {
        new HelloPay([
            'shopConfig' => 'AAEAAADoKU7YItWSEhYpJRb0fV842USafymz4Vs2onKOPsMPlnU9LFrt/AAEAAAgfi0NagriqYanhi9n07WBzO97L9uLWSRpFW5Yk_yhzXuhddRfA/413974t5da819d4495d7c45ce3ef036a'
        ]);
    }

    /**
     *
     * @expectedException \HelloPay\Exceptions\HelloPayRequestParamException
     * @expectedExceptionMessage Value for the key priceAmount must not be empty!
     */
    public function testCreatePurchaseMissingParam()
    {
        $helloPay = new HelloPayProxy();
        $helloPay->createPurchase([]);
    }

    public function testCreatePurchaseSuccess()
    {
        $helloPay = new HelloPayProxy();
        $helloPay->getHttpClient()->shouldReceive('send')->once()->andReturn(
            new HelloPayResponse([
               'success' => true,
                'checkoutUrl' => 'https://hellopay.com/checkout?purchaseId=7981274913',
                'purchaseId' => '7981274913'
            ])
        );

        $response = $helloPay->createPurchase($this->getPurchaseData());

        $this->assertEquals('7981274913', $response->getPurchaseId());
        $this->assertEquals('https://hellopay.com/checkout?purchaseId=7981274913', $response->getCheckoutUrl());
    }

    public function testCreatePurchaseFailed()
    {
        $helloPay = new HelloPayProxy();
        $helloPay->getHttpClient()->shouldReceive('send')->once()->andReturn(
            new HelloPayResponse([
                'success' => false,
                'message' => 'There were errors'
            ])
        );

        $response = $helloPay->createPurchase($this->getPurchaseData());

        $this->assertFalse($response);
        $this->assertEquals('There were errors', $helloPay->getLastMessage());
    }

    protected function getPurchaseData()
    {
        $data = [];
        $data['priceAmount'] = "250";
        $data['priceCurrency'] = "SGD";
        $data['description'] = "Lazada purchase create";
        $data['merchantReferenceId'] = time();
        $data['purchaseReturnUrl'] = "http://yourdomain.com/hellopay/response";

        $data['basket'] = [];
        $data['basket']['basketItems'] = [];
        $data['basket']['basketItems'][] = [
            'name' => 'Item 1',
            'quantity' => 1,
            "amount" => "250",
            "taxAmount" => "0",
            "imageUrl" => "http://yourdomain.com/img/product.png",
            "currency" => "SGD"
        ];

        $data['basket']['shipping'] = "0.00";
        $data['basket']['totalAmount'] = "250";
        $data['basket']['currency'] = "SGD";

        $data['shippingAddress']['name'] = 'Testint Tom';
        $data['shippingAddress']['firstName'] = "Testint";
        $data['shippingAddress']['lastName'] = "Tom";
        $data['shippingAddress']['addressLine1'] = "Test Street 22";
        $data['shippingAddress']['province'] = "DKI Jakarta";
        $data['shippingAddress']['city'] = "Kab. Kepulauan Seribu";
        $data['shippingAddress']['country'] = "SG";
        $data['shippingAddress']['mobilePhoneNumber'] = "6563584754";
        $data['shippingAddress']['houseNumber'] = "House Number";
        $data['shippingAddress']['addressLine2'] = "Address Line2";
        $data['shippingAddress']['district'] = "Kepulauan Seribu Utara";
        $data['shippingAddress']['zip'] = "247964";


        $data['billingAddress'] = $data['shippingAddress'];


        $data['consumerData']['mobilePhoneNumber'] = "6563584754";
        $data['consumerData']['emailAddress'] = "test@test.com";
        $data['consumerData']['country'] = "SG";
        $data['consumerData']['language'] = "en";
        $data['consumerData']['dateOfBirth'] = "";
        $data['consumerData']['gender'] = "";
        $data['consumerData']['ipAddress'] = "127.0.0.1";
        $data['consumerData']['name'] = "Testint Tom";
        $data['consumerData']['firstName'] = "Testint";
        $data['consumerData']['lastName'] = "Tom";

        return $data;
    }
}
