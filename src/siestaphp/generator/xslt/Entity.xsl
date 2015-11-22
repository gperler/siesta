<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="text" encoding="utf-8"/>


    <xsl:template match="/">&lt;?php

        <xsl:if test="/entity/@namespace != ''">
            namespace <xsl:value-of select="/entity/@namespace"/>;
        </xsl:if>

        <xsl:if test="/entity/@dateTimeInUse = 'true'">
            use siestaphp\runtime\DateTime;
            use siestaphp\runtime\Factory;
        </xsl:if>
        use siestaphp\runtime\ArrayAccessor;
        use siestaphp\runtime\HttpRequest;
        use siestaphp\runtime\ServiceLocator;
        use siestaphp\runtime\Passport;
        use siestaphp\runtime\ORMEntity;
        use siestaphp\driver\ConnectionFactory;
        use siestaphp\driver\ResultSet;
        use siestaphp\util\StringUtil;
        use siestaphp\util\Util;

        <xsl:for-each select="/entity/referenceUseList/referenceUse">
            use <xsl:value-of select="@use"/>;
        </xsl:for-each>

        /**
         * Class <xsl:value-of select="/entity/@name"/> ORM for table <xsl:value-of select="/entity/@table"/>
         * @package <xsl:value-of select="/entity/@namespace"/>
         */
        class <xsl:value-of select="/entity/@name"/> implements ORMEntity {

            <xsl:call-template name="dbConstants"/>
            <!--
            <xsl:call-template name="getEntityByPrimaryKey"/>

            <xsl:call-template name="nmMappingFinder"/>

            <xsl:call-template name="referenceFinder"/>

            <xsl:call-template name="referenceDeleter"/>

            <xsl:call-template name="deleteByPrimaryKey"/>

            <xsl:call-template name="customStoredProcedures"/>

            <xsl:call-template name="executeStoredProcedure"/>

            <xsl:call-template name="createInstanceFromResultSet"/>

            <xsl:call-template name="batchSaver"/>-->

            <xsl:call-template name="attributes"/>

            <xsl:call-template name="constructor"/>

            <xsl:call-template name="validate"/>

            <xsl:call-template name="save"/>

            <xsl:call-template name="fromResultSet"/>

            <xsl:call-template name="fromHttpRequest"/>

            <xsl:call-template name="fromArray"/>

            <xsl:call-template name="toArray"/>

            <xsl:call-template name="linkRelations"/>

            <xsl:call-template name="attributeGetterSetter"/>

            <xsl:call-template name="referenceGetterSetter"/>

            <xsl:call-template name="collectorGetterSetter"/>

            <xsl:call-template name="arePrimaryKeyIdentical"/>
        }
    </xsl:template>


    <xsl:template name="dbConstants">
        const TABLE_NAME = "<xsl:value-of select="/entity/@table"/>";
        <xsl:if test="/entity/@delimit = 'true'">
            const DELIMIT_TABLE_NAME = "<xsl:value-of select="/entity/@delimitTableName"/>";
        </xsl:if>

        <!-- iterate attributes -->
        <xsl:for-each select="/entity/attribute">
            const COLUMN_<xsl:value-of select="@DBColumnConstant"/> = "<xsl:value-of select="@dbName"/>";
        </xsl:for-each>

        <!-- iterate references -->
        <xsl:for-each select="/entity/reference">
            <xsl:for-each select="column">
                const COLUMN_<xsl:value-of select="@DBColumnConstant"/> = "<xsl:value-of select="@databaseName"/>";
            </xsl:for-each>
        </xsl:for-each>
    </xsl:template>

    <xsl:template name="getEntityByPrimaryKey">
        <xsl:if test="/entity/@hasPrimaryKey = 'true'">
        /**
        <xsl:for-each select="/entity/pkColumn">
          * @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
        </xsl:for-each>
         * @param string $connectionName
         * @return <xsl:value-of select="/entity/@constructClass"/>
         */
        public static function getEntityByPrimaryKey(<xsl:for-each select="/entity/pkColumn">$<xsl:value-of select="@name"/>,</xsl:for-each> $connectionName=null)
        {
            if (<xsl:for-each select="/entity/pkColumn">!$<xsl:value-of select="@name"/><xsl:if test="position() != last()"> or </xsl:if></xsl:for-each>) {
                return null;
            }
            $connection = ConnectionFactory::getConnection($connectionName);
            <xsl:for-each select="/entity/pkColumn">
                $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
            </xsl:for-each>

            $resultList = self::executeStoredProcedure("CALL <xsl:value-of select="/entity/standardStoredProcedures/@findByPrimaryKey"/>(<xsl:for-each select="/entity/pkColumn">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
            return Util::getFromArray($resultList, 0);
        }

            <xsl:if test="/entity/@delimit = 'true'">
                /**
                 * @param DateTime $validAt
                <xsl:for-each select="/entity/pkColumn">
                    * @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
                </xsl:for-each>
                * @param string $connectionName
                * @return <xsl:value-of select="/entity/@constructClass"/>
                */
                public static function getEntityByPrimaryKeyAtTime(DateTime $validAt, <xsl:for-each select="/entity/pkColumn">$<xsl:value-of select="@name"/>,</xsl:for-each> $connectionName=null)
                {
                    if ($validAt === null or <xsl:for-each select="/entity/pkColumn">$<xsl:value-of select="@name"/> === null <xsl:if test="position() != last()"> or </xsl:if></xsl:for-each>) {
                        return null;
                    }

                    $connection = ConnectionFactory::getConnection($connectionName);

                    <xsl:for-each select="/entity/pkColumn">
                        $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
                    </xsl:for-each>
                    $validAt = $validAt->getSQLDateTime();

                    $resultList = self::executeStoredProcedure("CALL <xsl:value-of select="/entity/standardStoredProcedures/@findByPrimaryKeyDelimit"/>('$validAt',<xsl:for-each select="/entity/pkColumn">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
                    return Util::getFromArray($resultList, 0);
                }
            </xsl:if>
        </xsl:if>

    </xsl:template>


    <xsl:template name="referenceFinder">
        <xsl:for-each select="/entity/reference">
            /**
             * <xsl:for-each select="column">
             * @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
             * </xsl:for-each>
             * @param string $connectionName
             * @return <xsl:value-of select="/entity/@constructClass"/>[]
             */
            public static function getEntityBy<xsl:value-of select="@methodName"/>Reference(<xsl:for-each select="column">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName = null)
            {
                $connection = ConnectionFactory::getConnection($connectionName);
                <xsl:for-each select="column">
                    $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
                </xsl:for-each>
                return self::executeStoredProcedure("CALL <xsl:value-of select="@spFinderName"/>(<xsl:for-each select="column">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
            }

            <xsl:variable name="referenceMethodName" select="@methodName"/>
            <xsl:variable name="columnList" select="column"/>
            <xsl:for-each select="filter">
                /**
                 * <xsl:for-each select="$columnList">
                 * @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
                 * </xsl:for-each>
                 * <xsl:for-each select="parameter">
                 * @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
                 * </xsl:for-each>
                 * @param string $connectionName
                 * @return <xsl:value-of select="/entity/@constructClass"/>[]
                 */
                public static function getEntityBy<xsl:value-of select="$referenceMethodName"/>Filter<xsl:value-of select="@name"/>(<xsl:for-each select="$columnList">$<xsl:value-of select="@name"/>,</xsl:for-each><xsl:for-each select="parameter">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName = null)
                {
                    $connection = ConnectionFactory::getConnection($connectionName);
                    <xsl:for-each select="$columnList">
                        $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
                    </xsl:for-each>
                    return self::executeStoredProcedure("CALL <xsl:value-of select="@spName"/>(<xsl:for-each select="$columnList">'$<xsl:value-of select="@name"/>',</xsl:for-each><xsl:for-each select="parameter">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
                }
            </xsl:for-each>
        </xsl:for-each>
    </xsl:template>


    <xsl:template name="nmMappingFinder">
        <xsl:for-each select="/entity/nmMapping">
            /**
             * <xsl:for-each select="pkColumn">
             * @param <xsl:value-of select="@phpType"/> $<xsl:value-of select="@name"/>
             * </xsl:for-each>
             * @param string $connectionName
             * @return <xsl:value-of select="/entity/@constructClass"/>[]
             */
            public static function <xsl:value-of select="@phpMethodName"/>(<xsl:for-each select="pkColumn">$<xsl:value-of select="@name"/></xsl:for-each>,$connectionName = null) {
                $connection = ConnectionFactory::getConnection($connectionName);
                <xsl:for-each select="pkColumn">
                    $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
                </xsl:for-each>
                return self::executeStoredProcedure("CALL <xsl:value-of select="@spName"/>(<xsl:for-each select="pkColumn">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
            }
        </xsl:for-each>
    </xsl:template>


    <xsl:template name="referenceDeleter">
        <xsl:for-each select="/entity/reference">
            /**
             * <xsl:for-each select="column">
             * @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
             * </xsl:for-each>
             * @param string $connectionName
             * @return void
             */
            public static function deleteEntityBy<xsl:value-of select="@methodName"/>Reference(<xsl:for-each select="column">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName = null)
            {
                $connection = ConnectionFactory::getConnection($connectionName);
                 <xsl:for-each select="column">
                    $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
                </xsl:for-each>
                $connection->execute("CALL <xsl:value-of select="@spDeleterName"/>(<xsl:for-each select="column">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
            }
        </xsl:for-each>
    </xsl:template>


    <xsl:template name="deleteByPrimaryKey">
        <xsl:if test="/entity/@hasPrimaryKey = 'true'">
         /**
         <xsl:for-each select="/entity/pkColumn">
            * @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
         </xsl:for-each>
         * @param string $connectionName
         * @return <xsl:value-of select="/entity/@constructClass"/>
         */
        public static function deleteEntityByPrimaryKey(<xsl:for-each select="/entity/pkColumn">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName=null)
        {
            $connection = ConnectionFactory::getConnection($connectionName);
            <xsl:for-each select="/entity/pkColumn">
                $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
            </xsl:for-each>
            $connection->execute("CALL <xsl:value-of select="/entity/standardStoredProcedures/@deleteByPrimaryKey"/>(<xsl:for-each select="/entity/pkColumn">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
        }
        </xsl:if>
    </xsl:template>


    <xsl:template name="customStoredProcedures">
        <xsl:for-each select="/entity/storedProcedureList/storedProcedure">
            /**
             * <xsl:for-each select="parameter">
             *     @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
             * </xsl:for-each>
             * @param string $connectionName
                <xsl:choose>
                    <xsl:when test="@resultType='single'">
                        * @return <xsl:value-of select="/entity/@constructClass"/>
                    </xsl:when>
                    <xsl:when test="@resultType='list'">
                        * @return <xsl:value-of select="/entity/@constructClass"/>[]
                    </xsl:when>
                    <xsl:when test="@resultType='resultset'">
                        * @return ResultSet
                    </xsl:when>
                </xsl:choose>
             */
            public static function <xsl:value-of select="@name"/>(<xsl:for-each select="parameter">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName = null)
            {
                $connection = ConnectionFactory::getConnection($connectionName);

                <xsl:call-template name="createSPCall">
                    <xsl:with-param name="spName" select="@databaseName"/>
                    <xsl:with-param name="parameterList" select="parameter"/>
                </xsl:call-template>

                <xsl:choose>
                    <xsl:when test="@resultType='single'">
                        return Util::getFromArray(self::executeStoredProcedure($spCall,$connectionName), 0);
                    </xsl:when>
                    <xsl:when test="@resultType='list'">
                        return self::executeStoredProcedure($spCall, $connectionName);
                    </xsl:when>
                    <xsl:when test="@resultType='resultset'">
                        return $connection->executeStoredProcedure($spCall);
                    </xsl:when>
                </xsl:choose>
            }
        </xsl:for-each>
    </xsl:template>


    <xsl:template name="createSPCall">
        <xsl:param name="spName"/>
        <xsl:param name="parameterList"/>
        <xsl:for-each select="$parameterList">
            <xsl:choose>
                <xsl:when test="@type = 'DateTime' and @dbType = 'DATETIME'">
                    $<xsl:value-of select="@name"/> = Util::quoteDateTime($<xsl:value-of select="@name"/>)
                </xsl:when>
                <xsl:when test="@type = 'DateTime' and @dbType = 'DATE'">
                    $<xsl:value-of select="@name"/> = Util::quoteDate($<xsl:value-of select="@name"/>)
                </xsl:when>
                <xsl:when test="@type = 'DateTime' and @dbType = 'TIME'">
                    $<xsl:value-of select="@name"/> = Util::quoteTime($<xsl:value-of select="@name"/>)
                </xsl:when>
                <xsl:otherwise>
                    $<xsl:value-of select="@name"/> = Util::quoteEscape($connection,$<xsl:value-of select="@name"/>);
                </xsl:otherwise>

            </xsl:choose>
        </xsl:for-each>
        $spCall = "CALL <xsl:value-of select="$spName"/>(<xsl:for-each select="$parameterList">$<xsl:value-of select="@name"/><xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)";
    </xsl:template>

    <xsl:template name="executeStoredProcedure">
        /**
         * @param string $invocation
         * @param string $connectionName
         * @return <xsl:value-of select="/entity/@constructClass"/>[]
         */
        private static function executeStoredProcedure($invocation, $connectionName=null)
        {
            $connection = ConnectionFactory::getConnection($connectionName);
            $objectList = [];
            $resultSet = $connection->executeStoredProcedure($invocation);
            while ($resultSet->hasNext()) {
                $objectList[] =  self::createInstanceFromResultSet($resultSet);
            }
            $resultSet->close();
            return $objectList;
        }
    </xsl:template>


    <xsl:template name="createInstanceFromResultSet">
        /**
         * @param ResultSet $res
         * @return <xsl:value-of select="/entity/@constructClass"/>
         */
        public static function createInstanceFromResultSet(ResultSet $res)
        {
            <xsl:choose>
                <xsl:when test="/entity/@constructFactory != ''">
                    $entity = <xsl:value-of select="/entity/@constructFactory"/>;
                </xsl:when>
                <xsl:otherwise>
                    $entity = new <xsl:value-of select="/entity/@constructClass"/>();
                </xsl:otherwise>
            </xsl:choose>

            $entity->initializeFromResultSet($res);
            return $entity;
        }
    </xsl:template>


    <xsl:template name="batchSaver">
       /**
        * @param $objectList <xsl:value-of select="/entity/@constructClass"/>[]
        * @param string $connectionName
        * @return void
        */
        public static function batchSave(array $objectList, $connectionName = null)
        {
            $batchCall = "";
            foreach($objectList as $object) {
                $batchCall .= $object->createSaveStoredProcedureCall();
            }
            $connection = ConnectionFactory::getConnection($connectionName);
            $connection->multiQuery($batchCall);
        }
    </xsl:template>


    <xsl:template name="attributes">
        /**
         * holds bool if this entity is existing in the database
         * @var bool
         */
        protected $_existing;

        /**
         * @var array
         */
        protected $_rawJSON;

        /**
         * @var array
         */
        protected $_rawSQLResult;

        <!-- iterate attributes -->
        <xsl:for-each select="/entity/attribute">
            <xsl:choose>
                <xsl:when test="@type='json'">
                    /**
                     * @var array
                     */
                    protected $<xsl:value-of select="@name"/>;
                </xsl:when>
                <xsl:otherwise>
                    /**
                     * @var <xsl:value-of select="@type"/>
                     */
                    protected $<xsl:value-of select="@name"/>;
                </xsl:otherwise>
            </xsl:choose>

        </xsl:for-each>

        <!-- iterate references -->
        <xsl:for-each select="/entity/reference">
           /**
            * @var <xsl:value-of select="construct/@name"/>
            */
            protected $<xsl:value-of select="@name"/>Obj;

            <!-- all referenced columns -->
            <xsl:for-each select="column">
                /**
                 * @var <xsl:value-of select="@type"/>
                 */
                 protected $<xsl:value-of select="@name"/>;
            </xsl:for-each>
        </xsl:for-each>

        <!-- iterate collectors -->
        <xsl:for-each select="/entity/collector">
            <xsl:choose>
                <xsl:when test="@type = '1n'">
                    /**
                     * @var <xsl:value-of select="@foreignConstructClass"/>[]
                     */
                    protected $<xsl:value-of select="@name"/>;
                </xsl:when>
                <xsl:when test="@type = 'nm'">
                   /**
                    * @var <xsl:value-of select="@foreignConstructClass"/>[]
                    */
                    protected $<xsl:value-of select="@name"/>;

                    /**
                    * @var <xsl:value-of select="@mapperClass"/>[]
                    */
                    protected $<xsl:value-of select="@name"/>Mapping;
                </xsl:when>
            </xsl:choose>
        </xsl:for-each>
    </xsl:template>


    <xsl:template name="constructor">
        /**
         * constructs a new instance of <xsl:value-of select="/entity/@name"/>
         */
        public function __construct()
        {
            $this->_existing = false;

            <!-- iterate attributes -->
            <xsl:for-each select="/entity/attribute">
                <xsl:choose>
                    <xsl:when test="@type='json'">
                        $this-><xsl:value-of select="@name"/> = null;
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:if test="@defaultValue != ''">
                            $this-><xsl:value-of select="@name"/> = <xsl:value-of select="@defaultValue"/>;
                        </xsl:if>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:for-each>

            <!-- iterate references -->
            <xsl:for-each select="/entity/reference">
                $this-><xsl:value-of select="@name"/>Obj = null;
                <xsl:for-each select="column">
                    $this-><xsl:value-of select="@name"/> = null;
                </xsl:for-each>
            </xsl:for-each>

            <!-- iterate collectors -->
            <xsl:for-each select="/entity/collector">
                $this-><xsl:value-of select="@name"/> = null;
                <xsl:if test="@type='nm'">
                    $this-><xsl:value-of select="@name"/>Mapping = [];
                </xsl:if>
            </xsl:for-each>
        }
    </xsl:template>


    <xsl:template name="save">
        /**
         * @param string $connectionName
         * @return string
         */
        public function createSaveStoredProcedureCall($connectionName = null)
        {

            <!-- make sure ids are given -->
            <xsl:for-each select="/entity/attribute[@primaryKey = 'true']">
                $this->get<xsl:value-of select="@methodName"/>(true);
            </xsl:for-each>

            $connection = ConnectionFactory::getConnection($connectionName);

            <!-- Build stored procedure call with all parameters -->
            $spCall = ($this->_existing) ? "CALL <xsl:value-of select="/entity/standardStoredProcedures/@update"/> " : "CALL <xsl:value-of select="/entity/standardStoredProcedures/@insert"/> ";
            $spCall .= "("

            <!-- iterate references -->
            <xsl:for-each select="/entity/reference">
                <xsl:if test="position() != 1">
                    . ","
                </xsl:if>
                <!-- iterate column list -->
                <xsl:for-each select="column">
                    <xsl:choose>
                        <xsl:when test="@type = 'bool' or @type = 'int' or @type='float'">
                            . Util::quoteNumber($this-><xsl:value-of select="@name"/>)
                        </xsl:when>
                        <xsl:when test="@type = 'string'">
                            . Util::quoteEscape($connection, $this-><xsl:value-of select="@name"/>)
                        </xsl:when>
                    </xsl:choose>
                    <xsl:if test="position() != last()">."," </xsl:if>
                </xsl:for-each>
            </xsl:for-each>

            <!-- iterate attributes -->
            <xsl:for-each select="/entity/attribute[@transient='false']">
                <xsl:if test="(position() = 1) and (/entity/@hasReferences = 'true')">
                    . ","
                </xsl:if>
                <xsl:if test="position() != 1">
                    . ","
                </xsl:if>
                <xsl:choose>
                    <xsl:when test="@type = 'bool' or @type = 'int' or @type = 'float'">
                        . Util::quoteNumber($this-><xsl:value-of select="@name"/>)
                    </xsl:when>
                    <xsl:when test="@type = 'string'">
                        . Util::quoteEscape($connection, $this-><xsl:value-of select="@name"/>)
                    </xsl:when>
                    <xsl:when test="@type = 'DateTime'">
                        <xsl:choose>
                            <xsl:when test="@dbType = 'DATETIME'">
                                . Util::quoteDateTime($this-><xsl:value-of select="@name"/>)
                            </xsl:when>
                            <xsl:when test="@dbType='DATE'">
                                . Util::quoteDate($this-><xsl:value-of select="@name"/>)
                            </xsl:when>
                            <xsl:when test="@dbType='TIME'">
                                . Util::quoteTime($this-><xsl:value-of select="@name"/>)
                            </xsl:when>
                        </xsl:choose>
                    </xsl:when>
                    <xsl:when test="@type='json'">
                        . Util::quoteEscape($connection, json_encode($this-><xsl:value-of select="@name"/>))
                    </xsl:when>
                </xsl:choose>
            </xsl:for-each>. ");";
            return $spCall;
        }

        /**
         * @param bool $cascade
         * @param Passport $passport
         * @param string $connectionName
         * @return void
         */
        public function save($cascade = false, $passport=null, $connectionName=null)
        {

            <!-- make sure ids are given -->
            <xsl:for-each select="/entity/attribute[@primaryKey = 'true']">
                $this->get<xsl:value-of select="@methodName"/>(true);
            </xsl:for-each>

            if (!$passport) {
                $passport = new Passport();
            }

            if (!$passport->furtherTravelAllowed('<xsl:value-of select="/entity/@table"/>',$this)) {
                return;
            }

            <!-- start cascade for referenced entities -->
            <xsl:for-each select="/entity/reference">
                if ($cascade and $this-><xsl:value-of select="@name"/>Obj !== null) {
                    $this-><xsl:value-of select="@name"/>Obj->save($cascade, $passport,$connectionName);
                }
            </xsl:for-each>

            <!-- Build stored procedure call with all parameters -->
            $spCall = $this->createSaveStoredProcedureCall($connectionName);

            $connection = ConnectionFactory::getConnection($connectionName);
            $connection->execute($spCall);
            $this->_existing = true;

            if (!$cascade) {
                return;
            }

            <!-- start cascade for collectors -->
            <xsl:for-each select="/entity/collector">
                <xsl:choose>
                    <xsl:when test="@type='1n'">
                        if ($this-><xsl:value-of select="@name"/> !== null) {
                            foreach($this-><xsl:value-of select="@name"/> as $c) {
                                $c->save($cascade, $passport,$connectionName);
                            }
                        }
                    </xsl:when>
                    <xsl:when test="@type='nm'">
                        if ($this-><xsl:value-of select="@name"/>Mapping !== null) {
                            foreach($this-><xsl:value-of select="@name"/>Mapping as $c) {
                                $c->save($cascade, $passport,$connectionName);
                            }
                        }
                    </xsl:when>
                </xsl:choose>
            </xsl:for-each>
        }
    </xsl:template>


    <xsl:template name="arePrimaryKeyIdentical">
        /**
         * @param <xsl:value-of select="/entity/@constructClass"/> $other
         * @return bool
         */
        public function arePrimaryKeyIdentical($other)
        {
            return
            <xsl:for-each select="/entity/attribute[@primaryKey = 'true']">
                $this->get<xsl:value-of select="@methodName"/>() === $other->get<xsl:value-of select="@methodName"/>()<xsl:if test="position() != last()"> and </xsl:if>
            </xsl:for-each>;
        }
    </xsl:template>


    <xsl:template name="validate">
        /**
         * @return bool
         */
        public function validate()
        {
            $isValid = true;
            <xsl:for-each select="/entity/attribute">
                <xsl:choose>
                    <xsl:when test="@type = 'bool' or @type='int' or @type='float'">
                        $isValid &amp;= Util::setType($this-><xsl:value-of select="@name"/>, "<xsl:value-of select="@type"/>");
                    </xsl:when>
                </xsl:choose>
            </xsl:for-each>

            <xsl:for-each select="/entity/reference">
                <!-- iterate column list -->
                <xsl:for-each select="column">
                    <xsl:choose>
                        <xsl:when test="@type = 'bool' or @type='int' or @type='float'">
                            $isValid &amp;= Util::setType( $this-><xsl:value-of select="@name"/>, "<xsl:value-of select="@type"/>");
                        </xsl:when>
                    </xsl:choose>
                </xsl:for-each>
            </xsl:for-each>

            return ($isValid === 1);
        }
    </xsl:template>


    <xsl:template name="fromResultSet">
       /**
        * @param ResultSet $res
        * @return void
        */
        public function initializeFromResultSet(ResultSet $res)
        {
            $this->_existing = true;
            $this->_rawSQLResult = $res->getNext();

            <!-- iterate attributes -->
            <xsl:for-each select="/entity/attribute[@transient='false']">
                <xsl:choose>
                    <xsl:when test="@type='bool'">
                        $this-><xsl:value-of select="@name"/> = $res->getBooleanValue('<xsl:value-of select="@dbName"/>');
                    </xsl:when>
                    <xsl:when test="@type='int'">
                        $this-><xsl:value-of select="@name"/> = $res->getIntegerValue('<xsl:value-of select="@dbName"/>');
                    </xsl:when>
                    <xsl:when test="@type='float'">
                        $this-><xsl:value-of select="@name"/> = $res->getFloatValue('<xsl:value-of select="@dbName"/>');
                    </xsl:when>
                    <xsl:when test="@type='string'">
                        $this-><xsl:value-of select="@name"/> = $res->getStringValue('<xsl:value-of select="@dbName"/>');
                    </xsl:when>
                    <xsl:when test="@type='DateTime'">
                        $this-><xsl:value-of select="@name"/> = $res->getDateTime('<xsl:value-of select="@dbName"/>');
                    </xsl:when>
                    <xsl:when test="@type='json'">
                        $this-><xsl:value-of select="@name"/> = json_decode($res->getStringValue('<xsl:value-of select="@dbName"/>'), true);
                    </xsl:when>
                </xsl:choose>
            </xsl:for-each>

            <!-- iterate references-->
            <xsl:for-each select="/entity/reference">
                <!-- iterate column list -->
                <xsl:for-each select="column">
                    <xsl:choose>
                        <xsl:when test="@type = 'bool'">
                            $this-><xsl:value-of select="@name"/> = $res->getBooleanValue('<xsl:value-of select="@databaseName"/>');
                        </xsl:when>
                        <xsl:when test="@type ='int'">
                            $this-><xsl:value-of select="@name"/> = $res->getIntegerValue('<xsl:value-of select="@databaseName"/>');
                        </xsl:when>
                        <xsl:when test="@type ='string'">
                            $this-><xsl:value-of select="@name"/> = $res->getStringValue('<xsl:value-of select="@databaseName"/>');
                        </xsl:when>
                        <xsl:when test="@type ='DateTime'">
                            $this-><xsl:value-of select="@name"/> = $res->getDateTime('<xsl:value-of select="@databaseName"/>');
                        </xsl:when>
                    </xsl:choose>
                </xsl:for-each>
            </xsl:for-each>
        }

        /**
         * @param string $key
         * @return mixed
         */
        public function getAdditionalColumn($key)
        {
            return Util::getFromArray($this->_rawSQLResult, $key);
        }

    </xsl:template>


    <xsl:template name="fromHttpRequest">
        /**
         * @param HttpRequest $req
         * @return void
         */
        public function initializeFromHttpRequest(HttpRequest $req)
        {

            <!-- iterate attributes -->
            <xsl:for-each select="/entity/attribute">
                <xsl:choose>
                    <xsl:when test="@type='bool'">
                        $this-><xsl:value-of select="@name"/> = $req->getBooleanValue('<xsl:value-of select="@name"/>');
                    </xsl:when>
                    <xsl:when test="@type='int'">
                        $this-><xsl:value-of select="@name"/> = $req->getIntegerValue('<xsl:value-of select="@name"/>');
                    </xsl:when>
                    <xsl:when test="@type='float'">
                        $this-><xsl:value-of select="@name"/> = $req->getFloatValue('<xsl:value-of select="@name"/>');
                    </xsl:when>
                    <xsl:when test="@type='string'">
                        $this-><xsl:value-of select="@name"/> = $req->getStringValue('<xsl:value-of select="@name"/>', <xsl:value-of select="@length"/>);
                    </xsl:when>
                    <xsl:when test="@type='DateTime'">
                        $this-><xsl:value-of select="@name"/> = $req->getDateTime('<xsl:value-of select="@name"/>');
                    </xsl:when>
                    <xsl:when test="@type='json'">
                        $this-><xsl:value-of select="@name"/> = json_decode($req->getStringValue('<xsl:value-of select="@name"/>'), true);
                    </xsl:when>
                </xsl:choose>
            </xsl:for-each>

            <!-- iterate references -->
            <xsl:for-each select="/entity/reference">
                <xsl:choose>
                    <xsl:when test="@foreignKeyType ='int'">
                        $this-><xsl:value-of select="@name"/>Id = $req->getIntegerValue('<xsl:value-of select="@name"/>');
                    </xsl:when>
                    <xsl:when test="@foreignKeyType ='string'">
                        $this-><xsl:value-of select="@name"/>Id = $req->getStringValue('<xsl:value-of select="@name"/>');
                    </xsl:when>
                </xsl:choose>
            </xsl:for-each>
        }
    </xsl:template>


    <xsl:template name="fromArray">
        /**
         * @param string $jsonString
         * @return void
         */
        public function fromJSON($jsonString)
        {
            $this->fromArray(json_decode($jsonString, true));
        }

        /**
         * @param string $key
         * @return mixed
         */
        public function getAdditionalJSON($key)
        {
            return Util::getFromArray($this->_rawJSON, $key);
        }

        /**
         * @param array $data
         * @return void
         */
        public function fromArray(array $data)
        {
            $this->_rawJSON = $data;
            $arrayAccessor = new ArrayAccessor($data);

            <!-- iterate attributes and extract information -->
            <xsl:for-each select="/entity/attribute">
                <xsl:choose>
                    <xsl:when test="@type='bool'">
                        $this-><xsl:value-of select="@name"/> = $arrayAccessor->getBooleanValue('<xsl:value-of select="@name"/>');
                    </xsl:when>
                    <xsl:when test="@type='int'">
                        $this-><xsl:value-of select="@name"/> = $arrayAccessor->getIntegerValue('<xsl:value-of select="@name"/>');
                    </xsl:when>
                    <xsl:when test="@type='float'">
                        $this-><xsl:value-of select="@name"/> = $arrayAccessor->getFloatValue('<xsl:value-of select="@name"/>');
                    </xsl:when>
                    <xsl:when test="@type='string'">

                        $this-><xsl:value-of select="@name"/> = $arrayAccessor->getStringValue('<xsl:value-of select="@name"/>'<xsl:if test="@transient='false'">, <xsl:value-of select="@length"/></xsl:if>);
                    </xsl:when>
                    <xsl:when test="@type='DateTime'">
                        $this-><xsl:value-of select="@name"/> = $arrayAccessor->getDateTime('<xsl:value-of select="@name"/>');
                    </xsl:when>
                </xsl:choose>
                <xsl:if test="@primaryKey = 'true'">
                    $this->_existing = ($this-><xsl:value-of select="@name"/> !== null);
                </xsl:if>
            </xsl:for-each>




            <!-- iterate references and initialize from array -->
            <xsl:for-each select="/entity/reference">
                $<xsl:value-of select="@name"/>Data = $arrayAccessor->get('<xsl:value-of select="@name"/>');
                if ($<xsl:value-of select="@name"/>Data) {
                    $this-><xsl:value-of select="@name"/>Obj = <xsl:call-template name="managerAccess"><xsl:with-param name="entityManager" select="manager"/></xsl:call-template>->newInstance();
                    $this-><xsl:value-of select="@name"/>Obj->fromArray($<xsl:value-of select="@name"/>Data);
                } else {
                    <xsl:for-each select="column">
                        $this-><xsl:value-of select="@name"/> = $arrayAccessor->get('<xsl:value-of select="@name"/>');
                    </xsl:for-each>
                }
            </xsl:for-each>

            <!-- iterate collections and initialize them -->
            <xsl:for-each select="/entity/collector">
                $<xsl:value-of select="@name"/>DataList = $arrayAccessor->getArray('<xsl:value-of select="@name"/>');
                if ($<xsl:value-of select="@name"/>DataList) {
                    $this-><xsl:value-of select="@name"/> = [];
                    foreach ($<xsl:value-of select="@name"/>DataList as $<xsl:value-of select="@name"/>Data) {
                        $obj = <xsl:call-template name="managerAccess"><xsl:with-param name="entityManager" select="manager"/></xsl:call-template>->newInstance();
                        $obj->fromArray($<xsl:value-of select="@name"/>Data);
                        $this-><xsl:value-of select="@name"/>[] = $obj;
                    }
                }
            </xsl:for-each>
        }
    </xsl:template>


    <xsl:template name="toArray">
        /**
         * @return string
         */
        public function toJSON()
        {
            return json_encode($this->toArray());
        }

        /**
          * @param Passport $passport
          *
          * @return array|null
          */
        public function toArray($passport = null)
        {
            if (!$passport) {
                $passport = new Passport();
            }

            if (!$passport->furtherTravelAllowed('<xsl:value-of select="/entity/@table"/>',$this)) {
                return null;
            }

            <!-- iterate attributes -->
            $result = [
            <xsl:for-each select="/entity/attribute">
                <xsl:choose>
                    <xsl:when test="@type='bool'">
                        "<xsl:value-of select="@name"/>" => $this-><xsl:value-of select="@name"/>
                    </xsl:when>
                    <xsl:when test="@type='int'">
                        "<xsl:value-of select="@name"/>" => $this-><xsl:value-of select="@name"/>
                    </xsl:when>
                    <xsl:when test="@type='float'">
                        "<xsl:value-of select="@name"/>" => $this-><xsl:value-of select="@name"/>
                    </xsl:when>
                    <xsl:when test="@type='string'">
                        "<xsl:value-of select="@name"/>" => $this-><xsl:value-of select="@name"/>
                    </xsl:when>
                    <xsl:when test="@type='DateTime'">
                        "<xsl:value-of select="@name"/>" => $this-><xsl:value-of select="@name"/> === null ? null : $this-><xsl:value-of select="@name"/>->getJSONDateTime()
                    </xsl:when>
                    <xsl:when test="@type='json'">
                        "<xsl:value-of select="@name"/>" => $this-><xsl:value-of select="@name"/>
                    </xsl:when>
                </xsl:choose>
                <xsl:if test="position() != last()">,</xsl:if>
            </xsl:for-each>];

            <!-- iterate references -->
            <xsl:for-each select="/entity/reference">
                if ($this-><xsl:value-of select="@name"/>Obj) {
                    $result["<xsl:value-of select="@name"/>"] = $this-><xsl:value-of select="@name"/>Obj->toArray($passport);
                }

                <xsl:for-each select="column">
                    $result["<xsl:value-of select="@name"/>"] = $this-><xsl:value-of select="@name"/>;
                </xsl:for-each>
            </xsl:for-each>

            <!-- iterate collector -->
            <xsl:for-each select="/entity/collector">
                $result["<xsl:value-of select="@name"/>"] = [];
                if ($this-><xsl:value-of select="@name"/>) {
                    foreach($this-><xsl:value-of select="@name"/> as $<xsl:value-of select="@name"/>) {
                        $result["<xsl:value-of select="@name"/>"][] = $<xsl:value-of select="@name"/>->toArray($passport);
                    }
                }
            </xsl:for-each>

            return $result;
        }
    </xsl:template>


    <xsl:template name="linkRelations">
        /**
         * @param Passport $passport
         **/
        public function linkRelations($passport = null)
        {
            if (!$passport) {
                $passport = new Passport();
            }

            if (!$passport->furtherTravelAllowed('<xsl:value-of select="/entity/@table"/>',$this)) {
                return;
            }
            <!-- iterate references -->
            <xsl:for-each select="/entity/reference">
                if ($this-><xsl:value-of select="@name"/>Obj) {
                    $this->set<xsl:value-of select="@methodName"/>($this-><xsl:value-of select="@name"/>Obj);
                    $this-><xsl:value-of select="@name"/>Obj->linkRelations($passport);
                }
            </xsl:for-each>

            <!-- iterate collectors -->
            <xsl:for-each select="/entity/collector">
                if ($this-><xsl:value-of select="@name"/>) {
                    foreach($this-><xsl:value-of select="@name"/> as $<xsl:value-of select="@name"/>) {
                        $<xsl:value-of select="@name"/>->set<xsl:value-of select="@referenceMethodName"/>($this);
                        $<xsl:value-of select="@name"/>->linkRelations($passport);
                    }
                }
            </xsl:for-each>
        }
    </xsl:template>


    <xsl:template name="attributeGetterSetter">
        <xsl:for-each select="/entity/attribute">
            <xsl:choose>
                <xsl:when test="@type='json'">
                    /**
                     * @return null|array
                     */
                    public function get<xsl:value-of select="@methodName"/>() {
                        return $this-><xsl:value-of select="@name"/>;
                    }

                    /**
                     * @return string
                     */
                    public function get<xsl:value-of select="@methodName"/>JSON() {
                        return json_encode($this-><xsl:value-of select="@name"/>);
                    }

                    /**
                     * @param string $key;
                     * @return mixed
                     */
                    public function getFrom<xsl:value-of select="@methodName"/>($key) {
                        return Util::getFromArray($this-><xsl:value-of select="@name"/>, $key);
                    }

                    /**
                     * @param string $key;
                     * @param mixed $value;
                     * @return void
                     */
                    public function addTo<xsl:value-of select="@methodName"/>($key, $value) {
                        if ($this-><xsl:value-of select="@name"/> === null) {
                            $this-><xsl:value-of select="@name"/> = [];
                        }
                        $this-><xsl:value-of select="@name"/>[$key] = $value;
                    }

                    /**
                     * @param mixed $values;
                     * @return void
                     */
                    public function set<xsl:value-of select="@methodName"/>($values) {
                        $this-><xsl:value-of select="@name"/> = $values;
                    }

                </xsl:when>
                <xsl:otherwise>
                    /**
                     * <xsl:if test="@primaryKey = 'true'">
                     * @param bool $generateKey
                     * @param string $connectionName</xsl:if>
                     *
                     * @return <xsl:value-of select="@type"/>
                     */
                    public function get<xsl:value-of select="@methodName"/>(<xsl:if test="@primaryKey = 'true'">$generateKey=false, $connectionName = null</xsl:if>)
                    {

                        <xsl:if test="@primaryKey = 'true' and @autoValue != ''">
                            if ($generateKey and empty($this-><xsl:value-of select="@name"/>)) {
                                <xsl:choose>
                                    <xsl:when test="@autoValue='uuid'">
                                        $this-><xsl:value-of select="@name"/> = ServiceLocator::getUUIDGenerator()->uuid();
                                    </xsl:when>
                                    <xsl:when test="@autoValue='autoincrement'">
                                        $this-><xsl:value-of select="@name"/> = ConnectionFactory::getConnection($connectionName)->getSequence("<xsl:value-of select="/entity/@table"/>");
                                    </xsl:when>
                                </xsl:choose>
                            }
                        </xsl:if>
                        return $this-><xsl:value-of select="@name"/>;
                    }

                    /**
                     * @param <xsl:value-of select="@type"/> $value
                     * @return void
                     */
                    public function set<xsl:value-of select="@methodName"/>($value)
                    {
                        <xsl:choose>
                            <xsl:when test="@type='string'">$this-><xsl:value-of select="@name"/> = StringUtil::trimToNull($value, <xsl:value-of select="@length"/>);</xsl:when>
                            <xsl:otherwise>$this-><xsl:value-of select="@name"/> = $value;</xsl:otherwise>
                        </xsl:choose>
                    }
                </xsl:otherwise>
            </xsl:choose>
        </xsl:for-each>
    </xsl:template>




    <xsl:template name="referenceGetterSetter">

        <xsl:for-each select="/entity/reference">
            <!-- stores reference name for inner iteration -->
            <xsl:variable name="methodName" select="@methodName"/>
            /**
             * @param bool $forceReload
             * @return <xsl:value-of select="construct/@name"/>
             */
            public function get<xsl:value-of select="@methodName"/>($forceReload=false)
            {
                if ($this-><xsl:value-of select="@name"/>Obj === null or $forceReload) {
                    $this-><xsl:value-of select="@name"/>Obj = <xsl:call-template name="managerAccess"><xsl:with-param name="entityManager" select="manager"/></xsl:call-template>->getEntityByPrimaryKey(
                    <xsl:for-each select="column">
                        $this-><xsl:value-of select="@name"/><xsl:if test="position() != last()">,</xsl:if>
                    </xsl:for-each>);
                }
                <xsl:if test="@referenceCretorNeeded='true'">
                if ($this-><xsl:value-of select="@name"/>Obj !== null) {
                    $this-><xsl:value-of select="@name"/>Obj->set<xsl:value-of select="@foreignMethodName"/>($this,false);
                }
                </xsl:if>
                return $this-><xsl:value-of select="@name"/>Obj;
            }

            <xsl:for-each select="column">
                /**
                  * @return <xsl:value-of select="@type"/>
                  */
                public function get<xsl:value-of select="@methodName"/>()
                {
                    return $this-><xsl:value-of select="@name"/>;
                }
            </xsl:for-each>

            /**
             * @param <xsl:value-of select="construct/@name"/> $object
             * <xsl:if test="@referenceCretorNeeded='true'">@param bool $backlink</xsl:if>
             * @return void
             */
            public function set<xsl:value-of select="@methodName"/>($object<xsl:if test="@referenceCretorNeeded='true'">, $backlink=true</xsl:if>)
            {
                if ($object === null) {
                   $this-><xsl:value-of select="@name"/>Obj = null;
                    <xsl:for-each select="column">
                        $this-><xsl:value-of select="@name"/> = null;
                    </xsl:for-each>
                    return;
                }
                $this-><xsl:value-of select="@name"/>Obj = $object;
                <xsl:for-each select="column">
                    $this-><xsl:value-of select="@name"/> = $object->get<xsl:value-of select="@foreignMethodName"/>(true);
                </xsl:for-each>

                <!-- for bidirectional relationships set-->
                <xsl:if test="@referenceCretorNeeded='true'">
                if ($backlink) {
                    $object->set<xsl:value-of select="@foreignMethodName"/>($this,false);
                }
                </xsl:if>
            }
            <xsl:variable name="referenceName" select="@name"/>
            <xsl:for-each select="column">
                /**
                * @param <xsl:value-of select="@type"/> $id
                * @return void
                */
                public function set<xsl:value-of select="@methodName"/>($id)
                {
                    $this-><xsl:value-of select="@name"/> = $id;
                    $this-><xsl:value-of select="$referenceName"/>Obj = null;
                }
            </xsl:for-each>

        </xsl:for-each>
    </xsl:template>


    <xsl:template name="collectorGetterSetter">
        <xsl:for-each select="/entity/collector">
            <xsl:choose>
                <xsl:when test="@type='1n'">
                    /**
                     * @param bool $forceReload
                     * @param string $connectionName
                     * @return <xsl:value-of select="@foreignConstructClass"/>[]
                     */
                    public function get<xsl:value-of select="@methodName"/>($forceReload=false, $connectionName = null)
                    {
                        if ($this-><xsl:value-of select="@name"/> === null or $forceReload) {
                            $this-><xsl:value-of select="@name"/> = <xsl:call-template name="managerAccess"><xsl:with-param name="entityManager" select="manager"/></xsl:call-template>->getEntityBy<xsl:value-of select="@referenceMethodName"/>Reference(
                             <xsl:for-each select="/entity/attribute[@primaryKey = 'true']">
                                $this->get<xsl:value-of select="@methodName"/>(true),
                            </xsl:for-each>$connectionName);

                            foreach($this-><xsl:value-of select="@name"/> as $e) {
                                $e->set<xsl:value-of select="@referenceMethodName"/>($this);
                            }
                        }
                        return $this-><xsl:value-of select="@name"/>;
                    }

                    <xsl:variable name="methodName" select="@methodName"/>
                    <xsl:variable name="referenceMethodName" select="@referenceMethodName"/>
                    <xsl:variable name="foreignConstructClass" select="@foreignConstructClass"/>
                    <xsl:variable name="foreignEntiyManager" select="manager"/>
                    <xsl:for-each select="filter">
                        /**
                         * <xsl:for-each select="parameter">
                         * @param <xsl:value-of select="@type"/> $<xsl:value-of select="@name"/>
                         * </xsl:for-each>
                         * @param string $connectionName
                         * @return <xsl:value-of select="$foreignConstructClass"/>[]
                         */
                        public function get<xsl:value-of select="$methodName"/>Filter<xsl:value-of select="@name"/>(<xsl:for-each select="parameter">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName = null)
                        {
                            return <xsl:call-template name="managerAccess"><xsl:with-param name="entityManager" select="$foreignEntiyManager"/></xsl:call-template>->getEntityBy<xsl:value-of select="$referenceMethodName"/>Filter<xsl:value-of select="@name"/>(<xsl:for-each select="/entity/attribute[@primaryKey = 'true']">$this->get<xsl:value-of select="@methodName"/>(true), </xsl:for-each><xsl:for-each select="parameter">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName);
                        }
                    </xsl:for-each>

                    /**
                     * @return void
                     */
                    public function deleteAll<xsl:value-of select="@methodName"/>()
                    {
                        $this-><xsl:value-of select="@name"/> = null;
                    <xsl:call-template name="managerAccess"><xsl:with-param name="entityManager" select="manager"/></xsl:call-template>->deleteEntityBy<xsl:value-of select="@referenceMethodName"/>Reference(
                        <xsl:for-each select="/entity/attribute[@primaryKey = 'true']">
                            $this->get<xsl:value-of select="@methodName"/>(true)
                            <xsl:if test="position() != last()">,</xsl:if>
                        </xsl:for-each>);
                    }

                    /**
                     * @param <xsl:value-of select="@foreignConstructClass"/> $object
                     * @return void
                     */
                    public function addTo<xsl:value-of select="@methodName"/>(<xsl:value-of select="@foreignConstructClass"/> $object)
                    {
                        $object->set<xsl:value-of select="@referenceMethodName"/>($this);
                        if ($this-><xsl:value-of select="@name"/> === null) {
                            $this-><xsl:value-of select="@name"/> = [];
                        }
                        $this-><xsl:value-of select="@name"/>[] = $object;
                    }
                </xsl:when>

                <xsl:when test="@type='nm'">
                    /**
                     * @param bool $forceReload
                     * @return <xsl:value-of select="@foreignConstructClass"/>[]
                     */
                    public function get<xsl:value-of select="@methodName"/>($forceReload=false)
                    {
                        if ($this-><xsl:value-of select="@name"/> === null or $forceReload) {
                            $this-><xsl:value-of select="@name"/> = <xsl:call-template name="managerAccess"><xsl:with-param name="entityManager" select="manager"/></xsl:call-template>-><xsl:value-of select="@nmMethodName"/>(
                                <xsl:for-each select="/entity/attribute[@primaryKey = 'true']">
                                    $this->get<xsl:value-of select="@methodName"/>(true)<xsl:if test="position() != last()">,</xsl:if>
                                </xsl:for-each>
                            );
                        }
                        return $this-><xsl:value-of select="@name"/>;
                    }

                    /**
                     * @param <xsl:value-of select="@foreignConstructClass"/> $object
                     * @return void
                     */
                    public function addTo<xsl:value-of select="@methodName"/>(<xsl:value-of select="@foreignConstructClass"/> $object) {
                        $mappingElement = new <xsl:value-of select="@mapperClass"/>();
                        $mappingElement->set<xsl:value-of select="@nmThisMethodName"/>($this);
                        $mappingElement->set<xsl:value-of select="@nmForeignMethodName"/>($object);
                        $this-><xsl:value-of select="@name"/>Mapping[] = $mappingElement;
                    }

                </xsl:when>
            </xsl:choose>



        </xsl:for-each>
    </xsl:template>

    <xsl:template name="managerAccess">
        <xsl:param name="entityManager"/>
        <xsl:choose>
            <xsl:when test="$entityManager/@constructFactory = ''">
                <xsl:value-of select="$entityManager/@constructClass"/>::getInstance()
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$entityManager/@constructFactory"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

</xsl:stylesheet>