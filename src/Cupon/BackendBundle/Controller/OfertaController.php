<?php

namespace Cupon\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cupon\OfertaBundle\Entity\Oferta;
use Cupon\BackendBundle\Form\OfertaType;

/**
 * Oferta controller.
 *
 */
class OfertaController extends Controller
{

    /**
     * Lists all Oferta entities.
     *
     */
    public function indexAction()
    {
    	// Si el usuario no ha seleccionado ninguna ciudad, seleccionar
    	// la ciudad por defecto
    	$sesion = $this->getRequest()->getSession();
    	if (null == $slug = $sesion->get('ciudad')) {
    		$slug = $this->container->getParameter('cupon.ciudad_por_defecto');
    		$sesion->set('ciudad', $slug);
    	}
    	
        $em = $this->getDoctrine()->getManager();
        
        $paginador=$this->get('ideup.simple_paginator');
        $paginador->setItemsPerPage(19);

        $entities = $paginador->paginate(
        		$em->getRepository('CiudadBundle:Ciudad')->queryTodasLasOfertas($slug)
        		)->getResult();

        return $this->render('BackendBundle:Oferta:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Oferta entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Oferta();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('oferta_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Oferta:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Oferta entity.
     *
     * @param Oferta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Oferta $entity)
    {
        $form = $this->createForm(new OfertaType(), $entity, array(
            'action' => $this->generateUrl('oferta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Oferta entity.
     *
     */
    public function newAction()
    {
        $entity = new Oferta();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Oferta:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Oferta entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfertaBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Oferta:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Oferta entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfertaBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Oferta:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Oferta entity.
    *
    * @param Oferta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Oferta $entity)
    {
        $form = $this->createForm(new OfertaType(), $entity, array(
            'action' => $this->generateUrl('oferta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Oferta entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfertaBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('oferta_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Oferta:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Oferta entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OfertaBundle:Oferta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Oferta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('oferta'));
    }

    /**
     * Creates a form to delete a Oferta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('oferta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
