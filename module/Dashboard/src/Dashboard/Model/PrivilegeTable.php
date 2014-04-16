<?php

/**
 * Description of Privileges
 *
 * @author fragote
 */
namespace Dashboard\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class PrivilegeTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
     
    public function getTableGateway()
    {
        return $this->tableGateway;
    }
 
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getMenuByUser($userId)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql
                  ->select()
                  ->from('privilege')
                  ->join(
                        array('m' => 'menu'),
                        'm.id = privilege.menu_id',
                        array('*')
                    )
                  ->join(
                        array('r' => 'role'),
                        'r.id = privilege.role_id',
                        array('*')
                    )
                    ->join(
                        array('u' => 'user'),
                        'u.role_id = r.id',
                        array('*')
                    )
                    ->where(array('u.id' => $userId));
        $stmt = $sql->prepareStatementForSqlObject($select); 
        $results = $stmt ->execute(); 
        return $results;
    }
}
