<template>
  <div>
    <div class="m-b-20 ovf-hd">
      <div class="fl">
        <router-link class="btn-link-large add-btn" to="">
        <i :class="loading2?'el-icon-loading':'el-icon-plus'"></i>&nbsp;&nbsp;添加站点
      </router-link>
      </div>
      
      <div class="fl w-200 m-l-30">
      <el-autocomplete
        v-model="keywords"
        icon="search"
        placeholder="请输入网站名称" @keyup.enter.native="rePageSize()"
        :fetch-suggestions="getSites"
        @select="handleSelect">
          
      </el-autocomplete>
    </div>
    <div class="fl w-30 m-l-30"><el-button type="text" :disabled="true">指派给：</el-button></div>
    <div class="fl w-200">
      
      <span class="wraper">
      <el-select v-model="user" filterable placeholder="输入搜索用户名" clearable @change="assignTo()">
          <el-option
            v-for="item in users"
            :key="item.id"
            :label="item.label"
            :value="item.id">
            <span style="float: left">{{ item.label }}</span>
            <span style="float: right; color: #8492a6; font-size: 13px">{{ item.job_num }}</span>
          </el-option>
        </el-select></span>
        </div>
    </div>
    
    <el-table
    :data="tableData"
    style="width: 100%"
    @selection-change="selectItem">
      <el-table-column
      type="selection"
      width="50">
      </el-table-column>
      <el-table-column
      label="站点名称"
      prop="cn_project">
      </el-table-column>
      <el-table-column
      label="指派">
      <template scope="scope">
        <el-tag v-if="scope.row.agentname">{{scope.row.agentname}}</el-tag>
        <el-tag type="gray" v-else>未指派</el-tag>
      </template>
      </el-table-column>
      <el-table-column
      label="状态"
      prop="status"
      width="100">
        <template scope="scope">
          <div>
            {{ scope.row.status | status }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
      label="操作"
      width="200">
        <template scope="scope">
          <div>
            <span>
              <router-link :to="{ name: 'groupsEdit', params: { id: scope.row.id }}" class="btn-link edit-btn">
              编辑
              </router-link>
            </span>
            <span>
              <el-button
              size="small"
              type="danger"
              @click="confirmDelete(scope.row)">
              删除
              </el-button>
            </span>
          </div>
        </template>
      </el-table-column>
    </el-table>
    <div class="pos-rel p-t-20">
      
      <!-- <btnGroup :selectedData="multipleSelection" :type="'assignment'"></btnGroup> -->
      <div class="block pages">
				<el-pagination
				@current-change="handleCurrentChange"
				layout="sizes, prev, pager, next"
        :page-sizes="[15, 100, 200, 300, 400]"
        @size-change="handleSizeChange"
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
        tableData: [],
        multipleSelection: [],
        dataCount: null,
        currentPage: null,
        limit: 15,
        keywords: '',
        loading2: false,
        users: [],
        user: ''
      }
    },
    methods: {
      handleCurrentChange(page) {
        router.push({ path: this.$route.path, query: { keywords: this.keywords, page: page }})
      },
      handleSizeChange(pagesize) {
        router.push({ path: this.$route.path, query: { keywords: this.keywords, limit: pagesize }})
      },
      getCurrentPage() {
        let data = this.$route.query
        if (data) {
          if (data.page) {
            this.currentPage = parseInt(data.page)
          } else {
            this.currentPage = 1
          }
          if (data.limit) {
            this.limit = parseInt(data.limit)
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
      selectItem(val) {
        this.multipleSelection = val
      },
      assignTo(item) {
        if (!this.multipleSelection.length) {
          _g.toastMsg('warning', '请勾选数据')
          return
        }
        let ids = ''
        _.each(this.multipleSelection, (v, k) => {
          ids += v.id + ','
        })
        let data = {
          userid: this.user,
          selected: ids
        }
        _g.openGlobalLoading()
        this.apiPost('admin/illegalword/assignTo', data).then((res) => {
          _g.closeGlobalLoading()
          this.handelResponse(res, (data) => {
            _g.toastMsg('success', '分配成功')
            this.rePageSize()
            // setTimeout(() => {
            //   _g.shallowRefresh(this.$route.name)
            // }, 1500)
          })
        })
      },
      confirmDelete(item) {
        this.$confirm('确认删除该用户组?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/groups/', item.id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              setTimeout(() => {
                _g.shallowRefresh(this.$route.name)
              }, 1500)
            })
          })
        }).catch(() => {
          // handle error
        })
      },
      rePageSize() {
        this.loading2 = true
        this.getAllData()
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
      getSites(queryString, cb) {
        this.loading2 = true
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
              this.loading2 = false
            })
          })
        } else {
          this.timeout = setTimeout(() => {
            cb([{ value: '没有数据' }])
            this.loading2 = false
          }, 3000 * Math.random())
        }
      },
      getAllData() {
        this.loading = true
        let data = {
          keyword: this.keywords,
          page: this.currentPage,
          limit: this.limit
        }
        this.apiPost('admin/illegalword/getSites', data).then((res) => {
          // console.log('res = ', _g.j2s(res))
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.dataCount = data.dataCount
            this.loading2 = false
          })
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
        return _g.getHasRule('groups-save')
      },
      editShow() {
        return _g.getHasRule('groups-update')
      },
      deleteShow() {
        return _g.getHasRule('groups-delete')
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