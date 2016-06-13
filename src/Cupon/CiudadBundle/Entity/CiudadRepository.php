<?php
namespace Cupon\CiudadBundle\Entity;
use Doctrine\ORM\EntityRepository;


class CiudadRepository extends EntityRepository
{
	public function findCercanas($ciudad_id) {
		$em=$this->getEntityManager();
		
		$consulta=$em->createQuery('
				SELECT c
				FROM CiudadBundle:Ciudad c
				WHERE c.id != :id
				ORDER BY c.nombre ASC');
		$consulta->setMaxResults(5);
		$consulta->setParameter('id', $ciudad_id);
		return $consulta->getResult();
	}
	
//para utilizar el paginador simple paginator de ideup en el listado del backend, necesitamos dividir la consulta en 2 métodos
//el método queryXXXX prepara la consulta
//el método findXXXX ejecuta la consulta
	public function queryTodasLasOfertas($ciudad)
	{
		$em=$this->getEntityManager();
		
		$consulta=$em->createQuery('
				SELECT o, t
				FROM OfertaBundle:Oferta o
				JOIN o.tienda t JOIN o.ciudad c
				WHERE c.slug = :ciudad
				ORDER BY o.fechaPublicacion DESC');
		
		$consulta->setParameter('ciudad', $ciudad);
		return $consulta;
	}
	
	public function findTodasLasOfertas($ciudad)
	{
		return $this->queryTodasLasOfertas($ciudad)->getResult();
	}
}