<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="text" encoding="utf-8"/>


    <xsl:template match="/">&lt;?php

        <xsl:if test="/entity/manager/@namespace != ''">
            namespace <xsl:value-of select="/entity/manager/@namespace"/>;
        </xsl:if>

        use siestaphp\driver\ConnectionFactory;
        use siestaphp\driver\ResultSet;
        use siestaphp\util\Util;

        <xsl:for-each select="/entity/referenceUseList/referenceUse">
            use <xsl:value-of select="@use"/>;
        </xsl:for-each>

        /**
         * Class <xsl:value-of select="/entity/manager/@name"/> EntityManager for <xsl:value-of select="/entity/@name"/>
         * @package <xsl:value-of select="/entity/manager/@namespace"/>
         */
        class <xsl:value-of select="/entity/manager/@name"/> {

            <xsl:call-template name="getInstance"/>

            <xsl:call-template name="getEntityByPrimaryKey"/>

            <xsl:call-template name="referenceFinder"/>

            <xsl:call-template name="referenceDeleter"/>

            <xsl:call-template name="deleteByPrimaryKey"/>

            <xsl:call-template name="customStoredProcedures"/>

            <xsl:call-template name="executeStoredProcedure"/>

            <xsl:call-template name="createInstanceFromResultSet"/>

            <xsl:call-template name="batchSaver"/>

        }
    </xsl:template>

    <xsl:template name="getInstance">

        /**
        * @var <xsl:value-of select="/entity/manager/@name"/>
        */
        private static $instance;

        /**
        * @return <xsl:value-of select="/entity/manager/@name"/>
        */
        public static function getInstance() {
            if (self::$instance === null) {
                self::$instance = new static();
            }
            return self::$instance;
        }
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
        public function getEntityByPrimaryKey(<xsl:for-each select="/entity/pkColumn">$<xsl:value-of select="@name"/>,</xsl:for-each> $connectionName=null)
        {
            if (<xsl:for-each select="/entity/pkColumn">!$<xsl:value-of select="@name"/><xsl:if test="position() != last()"> or </xsl:if></xsl:for-each>) {
                return null;
            }
            $connection = ConnectionFactory::getConnection($connectionName);
            <xsl:for-each select="/entity/pkColumn">
                $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
            </xsl:for-each>

            $resultList = $this->executeStoredProcedure("CALL <xsl:value-of select="/entity/standardStoredProcedures/@findByPrimaryKey"/>(<xsl:for-each select="/entity/pkColumn">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
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
                public function getEntityByPrimaryKeyAtTime(DateTime $validAt, <xsl:for-each select="/entity/pkColumn">$<xsl:value-of select="@name"/>,</xsl:for-each> $connectionName=null)
                {
                    if ($validAt === null or <xsl:for-each select="/entity/pkColumn">$<xsl:value-of select="@name"/> === null <xsl:if test="position() != last()"> or </xsl:if></xsl:for-each>) {
                        return null;
                    }

                    $connection = ConnectionFactory::getConnection($connectionName);

                    <xsl:for-each select="/entity/pkColumn">
                        $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
                    </xsl:for-each>
                    $validAt = $validAt->getSQLDateTime();

                    $resultList = $this->executeStoredProcedure("CALL <xsl:value-of select="/entity/standardStoredProcedures/@findByPrimaryKeyDelimit"/>('$validAt',<xsl:for-each select="/entity/pkColumn">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
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
            public function getEntityBy<xsl:value-of select="@methodName"/>Reference(<xsl:for-each select="column">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName = null)
            {
                $connection = ConnectionFactory::getConnection($connectionName);
                <xsl:for-each select="column">
                    $<xsl:value-of select="@name"/> = $connection->escape($<xsl:value-of select="@name"/>);
                </xsl:for-each>
                return $this->executeStoredProcedure("CALL <xsl:value-of select="@spFinderName"/>(<xsl:for-each select="column">'$<xsl:value-of select="@name"/>'<xsl:if test="position() != last()">,</xsl:if></xsl:for-each>)");
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
             */
            public function deleteEntityBy<xsl:value-of select="@methodName"/>Reference(<xsl:for-each select="column">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName = null)
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
        public function deleteEntityByPrimaryKey(<xsl:for-each select="/entity/pkColumn">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName=null)
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
            public function <xsl:value-of select="@name"/>(<xsl:for-each select="parameter">$<xsl:value-of select="@name"/>,</xsl:for-each>$connectionName = null)
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
                        return $this->executeStoredProcedure($spCall, $connectionName);
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
        private function executeStoredProcedure($invocation, $connectionName=null)
        {
            $connection = ConnectionFactory::getConnection($connectionName);
            $objectList = array();
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
        public function createInstanceFromResultSet(ResultSet $res)
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
        */
        public function batchSave(array $objectList, $connectionName = null)
        {
            $batchCall = "";
            foreach($objectList as $object) {
                $batchCall .= $object->createSaveStoredProcedureCall();
            }
            $connection = ConnectionFactory::getConnection($connectionName);
            $connection->multiQuery($batchCall);
        }
    </xsl:template>


</xsl:stylesheet>