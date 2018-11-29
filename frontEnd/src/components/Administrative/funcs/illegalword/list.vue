<style>
.demo-box.demo-tag .el-tag+.el-tag {
  margin: 5px 0 5px 10px;
}
.fadeStart-enter-active {
  -webkit-animation: fadeStart .2s both ease-in-out;
  animation: fadeStart .2s both ease-in-out;
}

.fadeStart-leave-active {
  -webkit-animation: fadeEnd .2s both ease-in-out;
  animation: fadeEnd .2s both ease-in-out;
}

[v-cloak] > * {
  display: none;
}

@-webkit-keyframes fadeStart {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }
  to {
    opacity: 1;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    display: block;
  }
}
@keyframes fadeStart {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }
  to {
    opacity: 1;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    display: block;
  }
}
@-webkit-keyframes fadeEnd {
  0% {
    opacity: 1;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
  }
  to {
    opacity: 0;
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }
}
@keyframes fadeEnd {
  0% {
    opacity: 1;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
  }
  to {
    opacity: 0;
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }
}
</style>
<template>
	<div>
		<div class="m-b-20 ovf-hd">
			<div class="fl" v-show="addShow">
				<router-link class="btn-link-large add-btn" to="">
					<i class="el-icon-plus"></i>&nbsp;&nbsp;添加用户
				</router-link>
			</div>
			<div class="fl w-150 m-l-30">
				<el-select v-model="user" filterable placeholder="输入搜索用户名" clearable @change="rePageSize()" @clear="clearUser()">
          <el-option
            v-for="item in users"
            :key="item.id"
            :label="item.label"
            :value="item.id">
            <span style="float: left">{{ item.label }}</span>
            <span style="float: right; color: #8492a6; font-size: 13px">{{ item.job_num }}</span>
          </el-option>
        </el-select>
			</div>
      <div class="fl w-200 m-l-30">
        <el-autocomplete
          v-model="keywords"
          icon="search"
          clearable
          placeholder="请输入网站名称" @keyup.enter.native="rePageSize()"
          :fetch-suggestions="getSites"
          @select="handleSelect">
            
        </el-autocomplete>
      </div>
            <div class="fl w-200 m-l-30">
                <el-select v-model="limit" placeholder="请选择分页大小" @change="rePageSize()">                                     
                    <el-option v-for="item in pagesizes" :key="item.value" :label="item.value" :value="item.value">
                      <span style="float: left">{{item.value}}</span>
                      <span style="float: right; color: #8492a6; font-size: 13px">{{item.text}}</span>
                    </el-option>
                </el-select>
            </div>
            <div class="fl w-140 m-l-20"><el-button type="primary" :loading="loading2" @click="rePageSize()">当前显示 {{limit}}条/页</el-button></div>
            <div class="fl w-50 m-l-20"><el-button icon="el-icon-delete" @click="empty()"></el-button></div>
            
		</div>
        <transition name="fadeStart" v-cloak>
		<el-table
    v-loading="loading2"
		:data="tableData"
		style="width: 100%"
		@selection-change="selectItem"
    max-height="750"
    highlight-current-row>
			<el-table-column
			type="selection"
			width="50">
			</el-table-column>
			<el-table-column
			label="关键词"
      min-width="300">
            <template slot-scope="scope">
                <span class="demo-box demo-tag">
                    <el-tag size="medium" type="primary" v-for="(v,i) in scope.row.keys.split(',')" :key="scope.row.id+i+v">{{ v }}</el-tag>
                </span>
            </template>
			</el-table-column>      
			<el-table-column
			label="文章标题"
			prop="title"
      show-overflow-tooltip
			min-width="200">
			</el-table-column>
      <el-table-column
      label="负责人"
      show-overflow-tooltip
      min-width="100">
        <template scope="scope">        
          <el-tag v-if="scope.row.agentname">{{scope.row.agentname}}</el-tag>          
          <el-tag type="gray" v-else>未指派</el-tag>
        </template>
      </el-table-column>
			<el-table-column
			label="栏目名称"
			prop="class_name"
      show-overflow-tooltip
			min-width="100">
			</el-table-column>
            <el-table-column
			prop="sitename"
			label="网站名称"
      show-overflow-tooltip
      min-width="200">
			</el-table-column>
            <el-table-column
			label="链接"
      show-overflow-tooltip
      min-width="200">
            <template slot-scope="scope">
            <a :href='scope.row.url' target="_blank">
            {{ scope.row.url}}
            </a>
            </template>
			</el-table-column>
			<el-table-column
			label="状态"
      show-overflow-tooltip
			min-width="100">
        <template scope="scope">
          <div>
            <el-tag type="warning" v-if="scope.row.status==1">
              <i class="el-icon-search"></i>
              {{ scope.row.status | status }}
            </el-tag>
            <el-tag type="gray" v-else-if="scope.row.status==0">
              <i class="el-icon-circle-cross"></i>
              {{ scope.row.status | status }}
            </el-tag>
            <el-tag type="success" v-else-if="scope.row.status==2">
              <i class="el-icon-circle-check"></i>
              {{ scope.row.status | status }}
            </el-tag>
            <el-tag type="gray" v-else>
              {{ scope.row.status | status }}
            </el-tag>
          </div>
        </template>
			</el-table-column>
			<el-table-column
			label="操作"
      fixed="right"
			width="200">
        <template scope="scope">
          <div>
            <span>
              <el-button size="small" type="info" @click="changeStatus(scope.row, 1)">排查</el-button>
            </span>
            <span>
              <el-button size="small" type="success" @click="changeStatus(scope.row, 2)">完成</el-button>
            </span>
            <span>
              <el-button size="small" type="danger" @click="confirmDelete(scope.row)">删除</el-button>
            </span>
          </div>
        </template>
			</el-table-column>
		</el-table>
        </transition>
		<div class="pos-rel p-t-20">
			<btnGroup :selectedData="multipleSelection" :type="'illegalwords'"></btnGroup>
			<div class="block pages">
				<el-pagination
				@current-change="handleCurrentChange"
				layout="prev, pager, next"
				:page-size="limit"
				:current-page="currentPage"
				:total="dataCount">
				</el-pagination>
			</div>
		</div>
	</div>
</template>



<script>
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'

  export default {
    data() {
      return {
        pagesizes: [
          { value: 15, text: '显示15条' },
          { value: 30, text: '显示30条' },
          { value: 50, text: '显示50条!' },
          { value: 100, text: '显示100条!!' },
          { value: 1000, text: '小心！浏览器崩溃' }
        ],
        tableData: [],
        dataCount: null,
        currentPage: null,
        keywords: '',
        multipleSelection: [],
        loading2: false,
        users: [],
        user: '',
        dialogTableVisible: false,
        limit: 20
      }
    },
    methods: {
      empty() {
        _g.shallowRefresh(this.$route.name)
      },
      search() {
        router.push({ path: this.$route.path, query: { keywords: this.keywords, page: 1 }})
      },
      selectItem(val) {
        this.multipleSelection = val
      },
      handleCurrentChange(page) {
        router.push({ path: this.$route.path, query: { keywords: this.keywords, page: page }})
      },
      rePageSize() {
        this.loading2 = true
        this.getAllData()
      },
      clearUser() {
        this.user = ''
        this.rePageSize()
      },
      filterStatus(value, row) {
        return row.status === value
      },
      confirmDelete(item) {
        this.$confirm('确认删除该数据?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          _g.toastMsg('error', '无法删除')
          _g.shallowRefresh(this.$route.name)
          // this.apiDelete('admin/users/', item.id).then((res) => {
          //   _g.closeGlobalLoading()
          //   this.handelResponse(res, (data) => {
          //     _g.toastMsg('success', '删除成功')
          //     setTimeout(() => {
          //       _g.shallowRefresh(this.$route.name)
          //     }, 1500)
          //   })
          // })
        }).catch(() => {
          // catch error
        })
      },
      changeStatus(row, to) {
        this.$confirm('确认更改操作?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          let data = {
            rowid: row.id,
            changeto: to
          }
          _g.openGlobalLoading()
          this.apiPost('admin/illegalword/changeStatus', data).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '操作成功')
              this.rePageSize()
              // setTimeout(() => {
              //   _g.shallowRefresh(this.$route.name)
              // }, 1500)
            })
          })
        }).catch(() => {
          // catch error
        })
      },
      getAllData() {
        this.loading = true
        const data = {
          keywords: this.keywords,
          agent: this.user,
          page: this.currentPage,
          limit: this.limit
        }
        this.apiPost('admin/illegalword/index', data).then((res) => {
          // console.log('res = ', _g.j2s(res))
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.dataCount = data.dataCount
            this.loading2 = false
          })
        })
      },
      getCurrentPage() {
        let data = this.$route.query
        if (data) {
          if (data.page) {
            this.currentPage = parseInt(data.page)
          } else {
            this.currentPage = 1
          }
        }
      },
      getKeywords() {
        let data = this.$route.query
        if (data) {
          if (data.keywords) {
            this.keywords = data.keywords
          } else {
            this.keywords = ''
          }
        }
      },
      getSites(queryString, cb) {
        if (queryString !== '' || true) {
          let data = {
            keyword: queryString
          }
          this.apiPost('admin/illegalword/getSites', data).then((res) => {
            // console.log('res = ', _g.j2s(res))
            let sites = []
            this.handelResponse(res, (data) => {
              _.each(data.list, (v, k) => {
                sites.push({
                  label: v.cn_project,
                  key: k,
                  siteid: v.id,
                  value: v.cn_project
                })
              })
              cb(sites)
            })
          })
        } else {
          this.timeout = setTimeout(() => {
            cb([{ value: '没有数据' }])
          }, 3000 * Math.random())
        }
      },
      handleSelect(item) {
        this.rePageSize()
      },
      getUsers() {
        let data = {}
        this.apiPost('admin/illegalword/getUsers', data).then((res) => {
          // console.log('res = ', _g.j2s(res))
          let users = []
          this.handelResponse(res, (data) => {
            _.each(data.list, (v, k) => {
              users.push({
                label: v.nickname,
                id: v.id,
                job_num: v.username
              })
            })
            this.users = users
          })
        })
      },
      open5() {
        this.$notify.info({
          title: '分页',
          message: '当前每页将显示' + this.limit + '项数据',
          offset: 60
        })
      },
      init() {
        this.getKeywords()
        this.getCurrentPage()
        this.getAllData()
        this.getUsers()
      }
    },
    created() {
      this.init()
    },
    computed: {
      addShow() {
        return _g.getHasRule('users-save')
      },
      editShow() {
        return _g.getHasRule('users-update')
      },
      deleteShow() {
        return _g.getHasRule('users-delete')
      }
    },
    filters: {
      status(value) {
        if (value == 1) {
          return '已排查'
        } else if (value == 0) {
          return '待处理'
        } else if (value == 2) {
          return '已处理'
        } else {
          return '未知状态'
        }
      }
    },
    watch: {
      '$route' (to, from) {
        this.init()
      }
    },
    components: {
      btnGroup
    },
    mixins: [http]
  }
</script>