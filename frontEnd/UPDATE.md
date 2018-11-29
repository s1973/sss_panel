# 升级到最新版本踩过的坑😂 
## webpack相关配置
1. `webpack.optimize.UglifyJsPlugin`这个插件在webpack4里已经被移除了，应使用`config.optimization.minimize`代替，webpack4在mode为production时已经会自动优化
> ```
> optimization: {
>   minimize: true
> }
> ```
2. `webpack.optimize.OccurenceOrderPlugin is not a constructor`这个厉害了，改成`OccurrenceOrderPlugin`注意多了个`r`
3. `Error: webpack.optimize.CommonsChunkPlugin has been removed, please use config.optimization.splitChunks instead`提示很明显了，同样改用`config.optimization`
> ```
> optimization: {
>    // [new UglifyJsPlugin({...})]
>    minimize: true, 
>    // extract webpack runtime and module manifest to its own file in order to
>    // prevent vendor hash from being updated whenever app bundle is updated
>    runtimeChunk: {
>      name: 'manifest'
>    },
>    // split vendor js into its own file
>    splitChunks:{ 
>      chunks: 'async',
>      minSize: 30000,
>      minChunks: 1,
>      maxAsyncRequests: 5,
>      maxInitialRequests: 3,
>      name: false,
>      cacheGroups: {
>        vendor: {
>          name: 'vendor',
>          chunks: 'initial',
>          priority: -10,
>          reuseExistingChunk: false,
>          test: /node_modules\/(.*)\.js/
>        },
>        styles: {
>          name: 'styles',
>          test: /\.(scss|css)$/,
>          chunks: 'all',
>          minChunks: 1,
>          reuseExistingChunk: true,
>          enforce: true
>        }
>      }
>    }
>  }
> ```
4. `Make sure the rule matching .vue files include vue-loader in its use`这个问题想了很久，首先就是官方文档里的`vue-loader`需要在plugins里声明，但依然报错，后来直接清理了rule里.vue的相关loader就行了，应该是有冲突
5. webpack4里配置文件必须声明`mode`属性,默认为production，可设为none禁用默认处理
6. `ExtractTextPlugin`也寿终正寝了，改用`mini-css-extract-plugin`吧
7. `window is not defined`经查需要将ouput.globalObject设为`this`，默认为window (更正：使用`mini-css-extract-plugin`时同时使用`style-loader`也会报这个错，此时移除style-loader)
>```
> output: {
>    path: config.build.assetsRoot,
>    publicPath: process.env.NODE_ENV === 'production' ? config.build.assetsPublicPath : config.dev.assetsPublicPath,
>    filename: '[name].js',
>    globalObject: 'this'
>  },
>```

## ElementUi升级2.4.11
1. 引入css文件变更为`element-ui/lib/theme-chalk/index.css`
> `import 'element-ui/lib/theme-chalk/index.css'`
2. dialog组件似乎不能直接调用open方法打开了，也可能有其他冲突，这里记录下改用`visible.sync`属性控制
> `<el-dialog ref="dialog" :visible.sync="isshowChangePwdDialog" custom-class="w-400 h-300" title="修改密码">`
3. form里接口获取的值为string类型，若验证规则为number时则会出错，这里加上`parseInt()`函数
2. 还有些小细节，参考[ElementUI官方日志](http://element-cn.eleme.io/2.4/#/zh-CN/component/changelog)，一个一个问题搜索解决吧...