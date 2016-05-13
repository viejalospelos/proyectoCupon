<?php
namespace Cupon\TiendaBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class OwnerVoter implements VoterInterface
{
	public function supportsAttribute($attribute)
	{
		return 'ROLE_EDITAR_OFERTA'==$attribute;
	}
	
	public function supportsClass($class)
	{
		return true;
	}
//la lógica consiste en comparar si el id de la tienda que solicita el permiso coincide con el id asociado a la tienda de la oferta que se quiere modificar	
	public function vote(TokenInterface $token, $object, array $attributes)
	{
		foreach ($attributes as $attribute){
			if (false===$this->supportsAttribute($attribute)){
				continue;
			}
			
			$user=$token->getUser();
			
			if ($object->getTienda()->getId()===$user->getId()){
				return VoterInterface::ACCESS_GRANTED;
			}else{
				return VoterInterface::ACCESS_DENIED;
			}
		}
		
		return VoterInterface::ACCESS_ABSTAIN;
	}
}