<?php

    namespace framework\base;
    require (FRAMEWORK_PATH.'db/Sql.php');
    use framework\db\Sql;
    class Model extends Sql
    {
        protected $model;
        public function __construct()
        {
            // 获取数据库表名
            if (!$this->table) {
                // 获取模型类名称
                $this->model = $Path = explode('\\',get_class($this))[2];
                // 删除类名最后的 Model 字符
                $this->model = substr($this->model, 0, -5);
                // 数据库表名与类名一致
                $this->table = strtolower($this->model);

            }
        }
    }