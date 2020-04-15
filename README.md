# zqs-admin 通用管理后台

服务端：基于最新版tp6，
前端：layuiadmin（layuiadmin为收费版，这里没有上传，请购买后放于项目public/static目录）

# 安装
composer require zqscjj/zqs-admin
# 注意事项
1.若要支持 emoji 等表情 数据库需设置 CHARSET = utf8mb4
# builder构建组件
### 1. from表单构建器
示例：
```php
return -Builder::make('form')
                ->setDataUrl('add')
                ->setPageTitle('菜单')
                //->setPageTips('just a example.')
                //->setFormTitle('测试表单')
                //->addInput('菜单名称[:请输入菜单名称]','title','text','','required','有可能会显到面板左侧','width:200px;')
                //->addInput('路径','name')
                //->addFormItem('input','哈哈')
                ->addFormItems([
                    ['input','菜单名称[:请输入菜单名称]','title','','text','required','有可能会显到菜单面板','width:200px;'],
                    ['icon','图标','icon'],
                    ['input','路径','name','','text',''],
                    ['select','父级菜单','pid','',$this->rule_data,'required'],
                    ['switch','是否菜单','ismenu','0','是|否'],
                    ['input','条件','condition','','text',''],//可为空
                    ['input','备注','remark','','text',''],
                    ['input','权重','weigh','0','number','','权重越高越靠前','width:60px;'],
                ]);
                ->fetch();
```

### 2. table表格构建器
示例：
```php
return Builder::make('table')
                ->setDataUrl('index')
                ->setPageTitle('菜单管理')
                ->setPageTips('警告：非技术人员请勿操作！')
                //->addColumn('id','ID','checkbox')
                //->addColumn('title','名称')
                ->addColumns([
                    ['id','ID','checkbox'],
                    ['title','名称'],
                    ['icon','图标','icon','',['width'=>80]],
                    ['name','URL'],
                    ['ismenu','菜单显示','status',['否','是'],['width'=>100]],
                    ['create_time','创建时间'],
                    ['update_time','更新时间']
                ])
                //->addRightBtn('edit')
                //->addRightBtn('del')
                ->addRightBtns([
                    'edit',
                    'delete' => ['class'=>'reload confirm layui-btn-danger'],
                ])
                //->addSearchItem('名称','title')
                //->addSearchItem('url','name')
                /* ->addSearchItems([
                    ['名称','title','text','input'],
                    ['url','name','text'],
                    ['菜单','ismenu','','select','请选择',[''=>'不限',0=>'否',1=>'是']]
                ]) */
                //->addSearchItem('','keyword','text','input','菜单名')
                //->addTopBtn('delete')
                ->addTopBtns([
                    'add',
                    //'delete',// => ['url'=>'menu/more']
                ])
                ->setHeight('')
                ->setPage(['open'=>'false'])
                ->fetch();
```
# Demo
![](https://gooe.github.io/zqs-admin/demo/1.png)

![]((https://gooe.github.io/zqs-admin/demo/2.png)

![]((https://gooe.github.io/zqs-admin/demo/3.png)

![]((https://gooe.github.io/zqs-admin/demo/4.png)

![]((https://gooe.github.io/zqs-admin/demo/5.png)