<?php
//phpunit -c app src/Cupon/UsuarioBundle/Tests/Controller/DefaultControllerTest.php
namespace Cupon\UsuarioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
//generar usuarios mediante dataProvider de phpUnit	
   
    /**
     * @dataProvider generaUsuarios
     */
    public function testRegistro($usuario)
    {
    	$client=static ::createClient();
    	$client->followRedirect(true);
    	
    	$crawler=$client->request('GET', '/');
    	
    	$enlaceRegistro=$crawler->selectLink('Regístrate ya')->link();
    	$crawler=$client->click($enlaceRegistro);
    	//comprobamos que carga la página con el formulario buscando la expresión "Registrate gratis como usuario"
    	$this->assertGreaterThan(
    			0, 
    			$crawler->filter('html:contains("Registrate gratis como usuario")')->count(),
    			'Después de pulsar el botón Regístrate de la portada, se carga la página con el formulario de registro'
    			);
    	//ahora rellenamos el formulario y lo enviamos
    	$formulario=$crawler->selectButton('Registrarme')->form($usuario);
    	$crawler=$client->submit($formulario);
    	//comprobamos que la respuesta es correcta si el estado es un valor entre 200 y 300
    	$this->assertTrue($client->getResponse()->isSuccessful());
    	//si todo es correcto, nos logeará y nos generará una cookie de sesión. La cookie la obtenemos con getCookieJar()
    	//y dentro de la cookie buscamos el valor MOCKSESSID (en todos los test, la cookie de sesión tiene ese nombre)
    	//con el REGEX comprobamos que el navegador tiene una cookie de sesión válida
    	$this->assertRegExp('/(\d[a-z])+/', $client->getCookieJar()->get('MOCKSESSID', '/','localhost')->getValue(),'La aplicación ha enviado una cookie de sesión');
    	
    	//con la cookie de sesión sería suficiente pero podemos hacer una comprobación más cargando la página de mi perfil
    	
    	$perfil=$crawler->filter('aside section#login')->selectLink('Ver mi perfil')->link();
    	
    	$crawler=$client->click($perfil);
    	
    	$this->assertEquals(
    			$usuario['frontend_usuario[email]'], 
    			$crawler->filter('form input[name="frontend_usuario[email]"]')->attr('value'),
    			'El usuario se ha registrado correctamente y sus datos se han guardado en la base de datos'
    			);
    	
    	//por último, hay que borrar la mierda de la DB que hemos generado
    	$usuario=$this->em->getRepository('UsuarioBundle:Usuario')
    		->findOneByEmail($usuario['frontend_usuario[email]']);
    	$this->em->remove($usuario);
    	$this->em->flush();
    }
    
    public function generaUsuarios()
    {
    	return array(
    			array(
    					array(
    							'frontend_usuario[nombre]'           => 'Anónimo',
    							'frontend_usuario[apellidos]'        => 'Apellido1 Apellido2',
    							'frontend_usuario[fecha_nacimiento][day]'=>'3',
    							'frontend_usuario[fecha_nacimiento][month]'=>'4',
    							'frontend_usuario[fecha_nacimiento][year]'=>'1981',
    							'frontend_usuario[email]'            => 'anonimo'.uniqid().'@localhost.localdomain',
    							'frontend_usuario[password][first]'  => 'anonimo1234',
    							'frontend_usuario[password][second]' => 'anonimo1234',
    							'frontend_usuario[direccion]'        => 'Mi calle, Mi ciudad, 01001',
    							'frontend_usuario[dni]'              => '75135159r',
    							'frontend_usuario[numero_tarjeta]'   => '123456789012345',
    							'frontend_usuario[ciudad]'           => '101',
    							'frontend_usuario[permite_mail]'    => '1'
    					)
    			)
    	);    	   
    }
}
