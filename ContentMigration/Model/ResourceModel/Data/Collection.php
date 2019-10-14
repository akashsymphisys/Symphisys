<?php 
/**
 * Copyright 2019 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Symphisys\ContentMigration\Model\ResourceModel\Data;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	public function __construct( 
		\Magento\Framework\Model\ResourceModel\Db\Context $context,		
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		$resourcePrefix = null
	){       		
		parent::__construct($context, $resourcePrefix);		
    }protected function _construct()
    {
        $this->_init('content_migration', 'id');
    }
	public function getCollection($filters='')
    {
       
        $this->p_table = "content_migration";
        $this->c1_table = "block_migration_data";
        $this->c2_table = "category_migration_data";
        $this->c3_table = "cms_migration_data";
        $this->c4_table = "post_migration_data";
        $this->c5_table = "product_migration_data";       
       
        $adapter = $this->getConnection();
		$this->select = $adapter->select()->from(
            ['p' => $this->p_table],
            ['p.id', 'p.version_name','p.preview_image']
        )->joinLeft(
            ['c1' => 'block_migration_data'],
            'p.id = c1.content_migration_id',
			[]            
        )->joinLeft(
            ['c2' => 'category_migration_data'],
            'p.id = c2.content_migration_id',
			[]
        )->joinLeft(
            ['c3' => 'cms_migration_data'],
            'p.id = c3.content_migration_id' ,
			[]
        )->joinLeft(
            ['c4' => 'post_migration_data'],
            'p.id = c4.content_migration_id',
			[]
        )->joinLeft(
            ['c5' => 'product_migration_data'],
            'p.id = c5.content_migration_id',
			[]
        );
		/*if(count($filters)!=0 && !empty($filters['type'])){
			$this->select->where('p.ref_type = (?)',
            $filters['type']);
		}
		if(count($filters)!=0 && !empty($filters['store'])){
			$this->select->where('p.store_id = (?)',
            $filters['store']);
		}*/
		
		
		//->orderBy('p.id','DESCS');
		
		//echo $this->select;
		
		$query = $adapter->query($this->select);
        $rows  = $query->fetchAll();
		return $rows;
    }
	public function deleteItem($id){
		$this->p_table = "content_migration";
        $adapter = $this->getConnection();
		$this->select = $adapter->select()->from(
            ['p' => $this->p_table],
            ['p.id', 'p.ref_type']
        )->where(
            'p.id = (?)',
            $id
        );
		$query = $adapter->query($this->select);
        $rows  = $query->fetchAll();
		if(!empty($rows)){	
			$type = $rows[0]['ref_type'];
			if($type=='cms'){
				$status = $this->deleteCmsLayout($id);				
			}else if($type=='product'){
				$status = $this->deleteProductLayout($id);
			}else if($type=='category'){
				$status = $this->deleteCategoryLayout($id);
			}else if($type=='post'){
				$status = $this->deletePostLayout($id);
			}else if($type=='block'){
				$status = $this->deleteBlockLayout($id);
			}else{
				$status = 0;
			}				
			return  $status;	
		}
		
	}
	public function deleteCmsLayout($id){
		$this->p_table = "content_migration";       
        $this->c_table = "cms_migration_data";  
		$adapter = $this->getConnection();
		$adapter->delete(
			$this->c_table,
			['content_migration_id = ?' => $id]
		);
		$adapter->delete(
			$this->p_table,
			['id = ?' => $id]
		);	
		return 1;		
	}
	public function deleteProductLayout($id){
		$this->p_table = "content_migration";        
        $this->c_table = "product_migration_data";
		$adapter = $this->getConnection();
		$adapter->delete(
			$this->c_table,
			['content_migration_id = ?' => $id]
		);
		$adapter->delete(
			$this->p_table,
			['id = ?' => $id]
		);
		return 1;
	}
	public function deleteCategoryLayout($id){
		$this->p_table = "content_migration";        
        $this->c_table = "category_migration_data";
		$adapter = $this->getConnection();		
		$adapter->delete(
			$this->c_table,
			['content_migration_id = ?' => $id]
		);
		$adapter->delete(
			$this->p_table,
			['id = ?' => $id]
		);
		return 1;
	}
	public function deletePostLayout($id){
		$this->p_table = "content_migration";        
        $this->c_table = "post_migration_data";
		$adapter = $this->getConnection();
		$adapter->delete(
			$this->c_table,
			['content_migration_id = ?' => $id]
		);
		$adapter->delete(
			$this->p_table,
			['id = ?' => $id]
		);
		return 1;
	}
	public function deleteBlockLayout($id){
		$this->p_table = "content_migration";
        $this->c_table = "block_migration_data";
		$adapter = $this->getConnection();
		$adapter->delete(
			$this->c_table,
			['content_migration_id = ?' => $id]
		);
		$adapter->delete(
			$this->p_table,
			['id = ?' => $id]
		);
		return 1;
	}
	public function getLayoutData($id){
		$this->p_table = "content_migration";
        $adapter = $this->getConnection();
		$this->select = $adapter->select()->from(
            ['p' => $this->p_table],
            ['p.id', 'p.ref_type']
        )->where(
            'p.id = (?)',
            $id
        );
		$query = $adapter->query($this->select);
        $rows  = $query->fetchAll();
		if(!empty($rows)){	
			$type = $rows[0]['ref_type'];
			if($type=='cms'){
				$data = $this->getCmsLayout($id);				
			}else if($type=='product'){
				$data = $this->getProductLayout($id);
			}else if($type=='category'){
				$data = $this->getCategoryLayout($id);
			}else if($type=='post'){
				$data = $this->getPostLayout($id);
			}else if($type=='block'){
				$data = $this->getBlockLayout($id);
			}else{
				$data = '';
			}				
			return  $data;	
		}
	}
	public function getCmsLayout($id){		      
        $this->c_table = "cms_migration_data";  
		$adapter = $this->getConnection();
		$data = '';
		$this->select = $adapter->select()->from(
            ['c' => $this->c_table],
            ['c.content ']
        )->where(
            'c.content_migration_id = (?)',
            $id
        );
		$query = $adapter->query($this->select);
        $rows  = $query->fetchAll();
		if(isset($rows[0]['content'])){
			$data = $rows[0]['content'];
		}
		return $data;
	}
	public function getProductLayout($id){		        
        $this->c_table = "product_migration_data";
		$adapter = $this->getConnection();
		$data = '';
		$this->select = $adapter->select()->from(
            ['c' => $this->c_table],
            ['c.content ']
        )->where(
            'c.content_migration_id = (?)',
            $id
        );
		$query = $adapter->query($this->select);
        $rows  = $query->fetchAll();
		if(isset($rows[0]['content'])){
			$data = $rows[0]['content'];
		}
		return $data;
	}
	public function getCategoryLayout($id){		       
        $this->c_table = "category_migration_data";
		$adapter = $this->getConnection();
		$data = '';
		$this->select = $adapter->select()->from(
            ['c' => $this->c_table],
            ['c.content ']
        )->where(
            'c.content_migration_id = (?)',
            $id
        );
		$query = $adapter->query($this->select);
        $rows  = $query->fetchAll();
		if(isset($rows[0]['content'])){
			$data = $rows[0]['content'];
		}
		return $data;
	}
	public function getPostLayout($id){		      
        $this->c_table = "post_migration_data";
		$adapter = $this->getConnection();
		$data = '';
		$this->select = $adapter->select()->from(
            ['c' => $this->c_table],
            ['c.content ']
        )->where(
            'c.content_migration_id = (?)',
            $id
        );
		$query = $adapter->query($this->select);
        $rows  = $query->fetchAll();
		if(isset($rows[0]['content'])){
			$data = $rows[0]['content'];
		}
		return $data;
	}
	public function getBlockLayout($id){		
        $this->c_table = "block_migration_data";
		$adapter = $this->getConnection();
		$data = '';
		$this->select = $adapter->select()->from(
            ['c' => $this->c_table],
            ['c.content ']
        )->where(
            'c.content_migration_id = (?)',
            $id
        );
		$query = $adapter->query($this->select);
        $rows  = $query->fetchAll();
		if(isset($rows[0]['content'])){
			$data = $rows[0]['content'];
		}
		return $data;
	}
	public function saveLayout($data){

		$type =$data['refType'];
		echo $type;
		if(!empty($type)){	
			
			if($type=='cms'){
				$status = $this->saveCmsLayout($data);				
			}else if($type=='product'){
				$status = $this->saveProductLayout($data);
			}else if($type=='category'){
				$status = $this->saveCategoryLayout($data);
			}else if($type=='post'){
				$status = $this->savePostLayout($data);
			}else if($type=='block'){
				$status = $this->saveBlockLayout($data);
			}else{
				$status = 0;
			}				
			return  $status;	
		}
		
		//return 1;
	}

	public function saveCmsLayout($data){
		$this->p_table = "content_migration";        
        $this->c_table = "cms_migration_data";
		$adapter = $this->getConnection();
		$adapter->insert(
			$this->p_table,
			array(
			'version_name' => $data['version'],
			'store_id' => $data['storeId'],
			'ref_type' => $data['refType'],
			'admin_user' => $data['admin'])
		);
		$contentId = $adapter->lastInsertId('content_migration');
		$adapter->insert(
			$this->c_table,
			array(
			'content_migration_id' => $contentId,
			'ref_id' => $data['refId'],
			'content' => $data['content'])
		); 
		return 1;
	}
	public function saveProductLayout($data){
		$this->p_table = "content_migration";        
        $this->c_table = "product_migration_data";
		$adapter = $this->getConnection();
		$adapter->insert(
			$this->p_table,
			array(
			'version_name' => $data['version'],
			'store_id' => $data['storeId'],
			'ref_type' => $data['refType'],
			'admin_user' => $data['admin'])
		);
		$contentId = $adapter->lastInsertId('content_migration');
		$adapter->insert(
			$this->c_table,
			array(
			'content_migration_id' => $contentId,
			'ref_id' => $data['refId'],
			'content' => $data['content'])
		); 
		return 1;
	}	

	public function saveCategoryLayout($data){
		$this->p_table = "content_migration";        
        $this->c_table = "category_migration_data";
		$adapter = $this->getConnection();
		$adapter->insert(
			$this->p_table,
			array(
			'version_name' => $data['version'],
			'store_id' => $data['storeId'],
			'ref_type' => $data['refType'],
			'admin_user' => $data['admin'])
		);
		$contentId = $adapter->lastInsertId('content_migration');
		$adapter->insert(
			$this->c_table,
			array(
			'content_migration_id' => $contentId,
			'ref_id' => $data['refId'],
			'content' => $data['content'])
		); 
		return 1;
	}
	public function savePostLayout($data){
		$this->p_table = "content_migration";        
        $this->c_table = "block_migration_data";
		$adapter = $this->getConnection();
		$adapter->insert(
			$this->p_table,
			array(
			'version_name' => $data['version'],
			'store_id' => $data['storeId'],
			'ref_type' => $data['refType'],
			'admin_user' => $data['admin'])
		);
		$contentId = $adapter->lastInsertId('content_migration');
		$adapter->insert(
			$this->c_table,
			array(
			'content_migration_id' => $contentId,
			'ref_id' => $data['refId'],
			'content' => $data['content'])
		); 
		return 1;
	}		
	public function saveBlockLayout($data){
		$this->p_table = "content_migration";        
        $this->c_table = "post_migration_data";
		$adapter = $this->getConnection();
		$adapter->insert(
			$this->p_table,
			array(
			'version_name' => $data['version'],
			'store_id' => $data['storeId'],
			'ref_type' => $data['refType'],
			'admin_user' => $data['admin'])
		);
		$contentId = $adapter->lastInsertId('content_migration');
		$adapter->insert(
			$this->c_table,
			array(
			'content_migration_id' => $contentId,
			'ref_id' => $data['refId'],
			'content' => $data['content'])
		); 
		return 1;
	}
}
?>