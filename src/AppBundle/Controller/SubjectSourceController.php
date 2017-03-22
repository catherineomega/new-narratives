<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\SubjectSource;
use AppBundle\Form\SubjectSourceType;

/**
 * SubjectSource controller.
 *
 * @Route("/subject_source")
 */
class SubjectSourceController extends Controller
{
    /**
     * Lists all SubjectSource entities.
     *
     * @Route("/", name="subject_source_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:SubjectSource e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $subjectSources = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'subjectSources' => $subjectSources,
        );
    }
    /**
     * Search for SubjectSource entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:SubjectSource repository. Replace the fieldName with
	 * something appropriate, and adjust the generated search.html.twig
	 * template.
	 * 
     //    public function searchQuery($q) {
     //        $qb = $this->createQueryBuilder('e');
     //        $qb->where("e.fieldName like '%$q%'");
     //        return $qb->getQuery();
     //    }
	 *
     *
     * @Route("/search", name="subject_source_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:SubjectSource');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$subjectSources = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$subjectSources = array();
		}

        return array(
            'subjectSources' => $subjectSources,
			'q' => $q,
        );
    }
    /**
     * Full text search for SubjectSource entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:SubjectSource repository. Replace the fieldName with
	 * something appropriate, and adjust the generated fulltext.html.twig
	 * template.
	 * 
	//    public function fulltextQuery($q) {
	//        $qb = $this->createQueryBuilder('e');
	//        $qb->addSelect("MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') as score");
	//        $qb->add('where', "MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') > 0.5");
	//        $qb->orderBy('score', 'desc');
	//        $qb->setParameter('q', $q);
	//        return $qb->getQuery();
	//    }	 
	 * 
	 * Requires a MatchAgainst function be added to doctrine, and appropriate
	 * fulltext indexes on your SubjectSource entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="subject_source_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:SubjectSource');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$subjectSources = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$subjectSources = array();
		}

        return array(
            'subjectSources' => $subjectSources,
			'q' => $q,
        );
    }

    /**
     * Creates a new SubjectSource entity.
     *
     * @Route("/new", name="subject_source_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $subjectSource = new SubjectSource();
        $form = $this->createForm('AppBundle\Form\SubjectSourceType', $subjectSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subjectSource);
            $em->flush();

            $this->addFlash('success', 'The new subjectSource was created.');
            return $this->redirectToRoute('subject_source_show', array('id' => $subjectSource->getId()));
        }

        return array(
            'subjectSource' => $subjectSource,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a SubjectSource entity.
     *
     * @Route("/{id}", name="subject_source_show")
     * @Method("GET")
     * @Template()
	 * @param SubjectSource $subjectSource
     */
    public function showAction(SubjectSource $subjectSource)
    {

        return array(
            'subjectSource' => $subjectSource,
        );
    }

    /**
     * Displays a form to edit an existing SubjectSource entity.
     *
     * @Route("/{id}/edit", name="subject_source_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param SubjectSource $subjectSource
     */
    public function editAction(Request $request, SubjectSource $subjectSource)
    {
        $editForm = $this->createForm('AppBundle\Form\SubjectSourceType', $subjectSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The subjectSource has been updated.');
            return $this->redirectToRoute('subject_source_show', array('id' => $subjectSource->getId()));
        }

        return array(
            'subjectSource' => $subjectSource,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a SubjectSource entity.
     *
     * @Route("/{id}/delete", name="subject_source_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param SubjectSource $subjectSource
     */
    public function deleteAction(Request $request, SubjectSource $subjectSource)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subjectSource);
        $em->flush();
        $this->addFlash('success', 'The subjectSource was deleted.');

        return $this->redirectToRoute('subject_source_index');
    }
}