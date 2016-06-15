<?php

namespace Cupon\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cupon\CiudadBundle\Entity\Ciudad;
use Cupon\BackendBundle\Form\CiudadType;

/**
 * Ciudad controller.
 *
 */
class CiudadController extends Controller
{

    /**
     * Lists all Ciudad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CiudadBundle:Ciudad')->findAll();

        return $this->render('BackendBundle:Ciudad:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Ciudad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Ciudad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_ciudad_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Ciudad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Ciudad entity.
     *
     * @param Ciudad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ciudad $entity)
    {
        $form = $this->createForm(new CiudadType(), $entity, array(
            'action' => $this->generateUrl('backend_ciudad_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Ciudad entity.
     *
     */
    public function newAction()
    {
        $entity = new Ciudad();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Ciudad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ciudad entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CiudadBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Ciudad:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ciudad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CiudadBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Ciudad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Ciudad entity.
    *
    * @param Ciudad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ciudad $entity)
    {
        $form = $this->createForm(new CiudadType(), $entity, array(
            'action' => $this->generateUrl('backend_ciudad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Ciudad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CiudadBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('backend_ciudad_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Ciudad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Ciudad entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CiudadBundle:Ciudad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ciudad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('backend_ciudad'));
    }

    /**
     * Creates a form to delete a Ciudad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_ciudad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
