<?php
//phpunit -c app src/Cupon/OfertaBundle/Tests/Controller/

namespace Cupon\OfertaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
	// con la anotación @test no tenemos que seguir la nomenclatura testLaPortadaSiempre...()
    /** @test */
	public function laPortadaSiempreRedirigeAUnaCiudad()
    {
//el client actua como un navegador. Puede moverse entre páginas ($client->reload(); $client->back(); $client->forward())
//también incluye métodos con información útil (getRequest(), getResponse(), getHistory(), getCookieJar)
        $client = static::createClient();
//el primer argumento es el método y el segundo la url
        $crawler = $client->request('GET', '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode(), 
        		'La portada redirige a la portada de una ciudad (status 302)');
    }
    
    
    /** @test */    
    public function laPortadaSoloMuestraUnaOfertaActiva()
    {
    	$client=static::createClient();
//indicamos que siga todas las redirecciones    	
    	$client->followRedirect(true);
    	
    	$crawler=$client->request('GET', '/');
//navegando por el DOM    	
    	$ofertasActivas=$crawler->filter('article.oferta section.descripcion a:contains("Comprar")')->count();
    	$this->assertEquals(1, $ofertasActivas, 'La portada muestra una única oferta activa que se puede comprar');
    }
    
    
    /** @test */
    public function losUsuariosPuedenRegistrarseDesdeLaPortada()
    {
    	$cliente=static::createClient();
    	$cliente->request('GET', '/');
    	
    	$crawler=$cliente->followRedirect();
    	
    	$numeroEnlacesRegistrarse=$crawler->filter('html:contains("Regístrate ya")')->count();
    	
    	$this->assertGreaterThan(0, $numeroEnlacesRegistrarse, 'La portada mustra al menos un enlace o botón para registrarse');
    	
    }
    
    /** @test */
    public function losUsuariosAnonimosVenLaCiudadPorDefecto()
    {
    	$cliente=static::createClient();
    	$cliente->followRedirect(true);
    	$crawler=$cliente->request('GET', '/');
//con getContainer() tenemos acceso al contenedor de dependencias y podemos obtener un parámetro	
    	$ciudadPorDefecto=$cliente->getContainer()->getParameter('cupon.ciudad_por_defecto');
    	
    	$ciudadPortada=$crawler->filter('header nav select option [selected="selected"]')->attr('value');
    	
    	$this->assertEquals($ciudadPorDefecto, $ciudadPortada, 'Los usuarios anónimos ven seleccionada la ciudad por defecto');
    }
    
    /** @test */
    public function losUsuariosAnonimosDebenLoguearseParaPoderComprar()
    {
    	$client=static::createClient();
    	$client->request('GET', '/');
    	$crawler=$client->followRedirect();
//usamos selectLink() en vez de filter()porque al ser un link, se simplifica la acción
//el método link() selecciona el primer nodo de la lista y lo convierte en un objeto de tipo enlace
    	$comprar=$crawler->selectLink('Comprar')->link();
//simulamos un click    	
    	$client->click($comprar);
    	$crawler=$client->followRedirect();
    	
    	$this->assertRegExp('/.*\/usuario\/login_check', 
    			$crawler->filter('article form')->attr('action'),
    			'Después de pulsar el botón de comprar, el usuario anónimo ve el formulario de login');
    }
    
}
