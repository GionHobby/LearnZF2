<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace LearnZF2AuthenticationTest\Factory;

use PHPUnit_Framework_TestCase;
use LearnZF2Authentication\Factory\BasicAuthenticationAdapterFactory;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class BasicAuthenticationAdapterFactoryTest extends PHPUnit_Framework_TestCase
{
    /** @var BasicAuthenticationAdapterFactory */
    protected $basicFactory;

    /** @var ControllerManager */
    protected $controllerManager;

    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    public function setUp()
    {
        /** @var ControllerManager $controllerManager */
        $controllerManager = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $this->controllerManager = $controllerManager;

        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $dataArray = ['authentication_basic' => [
                        'adapter' => [
                            'config' => [
                                'accept_schemes' => 'basic',
                                'realm'          => 'authentication',
                                'nonce_timeout'  => 3600,
                            ],
                            'basic'  => dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/config/auth/basic.txt',
                        ],
                    ]];

        $serviceLocator->expects($this->once())
                       ->method('get')
                       ->with('Config')
                       ->willReturn($dataArray);

        $this->basicFactory = new BasicAuthenticationAdapterFactory();
        $this->serviceLocator = $serviceLocator;
    }

    public function testCreateService()
    {
        $basic = $this->basicFactory->createService($this->serviceLocator);
        $this->assertInstanceOf('Zend\Authentication\Adapter\Http', $basic);
    }
}
