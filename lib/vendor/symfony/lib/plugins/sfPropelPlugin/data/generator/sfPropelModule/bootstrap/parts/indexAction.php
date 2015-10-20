	public function executeIndex(sfWebRequest $request)
	{
<?php if (isset($this->params['with_propel_route']) && $this->params['with_propel_route']): ?>
		$this-><?php echo $this->getPluralName() ?> = $this->getRoute()->getObjects();
<?php else: ?>
		$c = new Criteria();
		$pager = new sfPropelPager('<?php echo $this->getModelClass() ?>', 10);
		$pager->setPage($request->getParameter('page', 1));
		$pager->setCriteria($c);
		$pager->init();
		$this->pager = $pager;
<?php endif; ?>
	}
