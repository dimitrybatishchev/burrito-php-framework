<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/3/13
 * Time: 12:58 PM
 */
namespace Burrito\Framework\ActiveRecord;

use PDO;

abstract class ActiveRecord {

    static private $_dbh = NULL;

    static public function setDb(PDO $dbh){
        self::$_dbh = $dbh;
    }

    abstract public function tableName();

    public function primaryKey(){
        return 'id';
    }

    private $_id = NULL;

    private $_data = NULL;

    public function __construct($id = NULL){
        if ($id == NULL) return;
        $this->_load($id);
    }

    protected $_dirty = FALSE;

    final public function __set($name, $value){
        if($this->_id !== NULL and !array_key_exists($name, $this->_data)){
            throw new Exception('Column "'.$name.'" does not exists in table "'.$this->tableName().'" in set.');
        }
        $this->_data[$name] = $value;
        $this->_dirty = TRUE;
        return $this;
    }

    private function _load($id){
        $this->_id = $id;
        // Issue the SELECT
        $rows = $this->_query( 'SELECT * FROM '.$this->tableName()
            .' WHERE `'.$this->primaryKey().'` = :id',
            array( ':id' => $id ) );
        if( count( $rows ) < 1 )
            throw new Exception( 'Bad ID in ActiveRecord Fetch for '.get_called_class().':'.$id );
        $this->_data = $rows[0];
        unset( $this->_data[ $this->primaryKey() ] );
    }

    public function save(){
        // Skip writing clean records.
        if( !$this->_dirty ) return TRUE;

        // This is used to bind the local object in the anonymous functions below.
        $base = $this;
        $params = array();

        // Handle UPDATEs
        if( $this->_id !== NULL ) {
            // This is a complex bit of PHP, I was feeling perl-ish, excuse me...
            $sql = 'UPDATE '.$this->tableName().' SET '
                . implode( ', ', array_map( function( $column ) use ( $base, &$params ) {
                    $params[':'.$column] = $base->$column;
                    return '`'.$column.'` = :'.$column;
                }, array_keys( $this->_data ) ) )
                . ' WHERE `'.$this->primaryKey().'` = "'.$this->_id.'"';
            $this->_query( $sql, $params );
            return TRUE;
        }

        // Handle INSERTs.
        $sql = 'INSERT INTO '.$this->tableName().' (`'
            . implode( '`, `', array_keys( $this->_data ) ).'`) VALUES (:'
            . implode( ',:', array_keys( $this->_data ) ).')';
        array_walk( $this->_data,
            function( $value, $column ) use ( $base, &$params ) {
                $params[':'.$column] = $value;
            } );
        $this->_query( $sql, $params );
        $this->_id = self::$_dbh->lastInsertId();
        return TRUE;
    }

    public function remove() {
        // Records that haven't been saved can't be removed.
        if( $this->_id == NULL )
            return;
        // Issue the DELETE SQL
        $sql = 'DELETE FROM '.$this->tableName()
            .' WHERE `'.$this->primaryKey().'` = :id';
        $this->_query( $sql, array( ':id' => $this->_id ) );
        $this->_id = NULL;
    }

    private function _query($sql, array $params = array()){
        $stmt = self::$_dbh->prepare( $sql );
        if( count( $params ) > 0 ){
            foreach( $params as $column => $value ){
                $stmt->bindValue( $column, $value );
            }
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    static public function all(){
        $ar_class = get_called_class();
        $base = new $ar_class( NULL );
        $sql = 'SELECT *,`'.$base->primaryKey().'` FROM '.$base->tableName();
        $rows = $base->_query( $sql );
        $results = array();
        if( count( $rows ) > 0 )
            foreach( $rows as $row )
                $results[] = $row[$base->primaryKey()];
        return $results;
    }

    static public function search( $sql_clause ) {
        $ar_class = get_called_class();
        $base = new $ar_class( NULL );
        $sql = 'SELECT *,`'.$base->primaryKey().'` FROM '.$base->tableName().' WHERE '.$sql_clause;
        $rows = $base->_query( $sql );
        $results = array();
        if( count( $rows ) > 0 )
            foreach( $rows as $row )
                $results[] = $row[$base->primaryKey()];
        return $results;
    }

}