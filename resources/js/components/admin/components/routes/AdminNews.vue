<template>
<div>
  <!-- <breads-crumb /> -->
  <div v-show="$route.name === 'news'">
    <v-card class="admin-categories">
      <v-dialog
        :max-width="430"
        v-model="deleteNewsDialog">
        <v-card>
          <v-card-title class="white--text grey darken-4">
            <v-icon large class="mr-2" color="white" v-text="'mdi-information'"></v-icon>
            Видалення
          </v-card-title>
          <v-card-title class="mt-4">
            Новину - буде видалено назавжди. Продовжити?
          </v-card-title>
          <v-card-text class="pt-4">
            <div class="d-flex justify-space-between">
              <span>
                <v-btn 
                  @click="deleteNews()"
                  :loading="loading"
                  color="error">
                  Так
                </v-btn>
              </span>
              <span>
                <v-btn 
                  @click="deleteNewsDialog = false" 
                  class="grey darken-2 white--text">
                  Hi
                </v-btn>
              </span>
            </div>
        </v-card-text>
        </v-card>
      </v-dialog>
      <v-card-title>
        Роздiл - Новини
      </v-card-title>
      <section class="section" id="section">
        <v-card-text>
          <v-card-title class="edit-title">
            Редагувати новини
            <v-spacer></v-spacer>
          </v-card-title>
          <v-row>
            <v-col cols="12">
              <v-btn 
                :to="{name: 'new-news'}"
                class="red lighten-1" dark>
                Додати новину&nbsp;
                <v-icon v-text="'mdi-plus'"></v-icon>
              </v-btn>
            </v-col>
          </v-row>
          <v-hover 
            v-for="(item, key) in materials"
            :key="key"
            v-slot:default="{ hover }">
            <v-card
              :elevation="hover ? 4 : 1"
              class="d-flex ml-3 mr-3 mb-5 material-card">
              <v-row id="material-card-container" class="ml-1 mr-1">
                <v-col cols="12" class="d-flex">
                    <v-col xs="12" sm="4" md="3" xl="2" class="d-flex align-items-center">
                      <div class="d-flex justify-center" style="border-radius: 5px; overflow: hidden;">
                        <!-- <v-icon v-if="!item.title_image" size="90" style="width: 100%;">
                          mdi-image
                        </v-icon> -->
                        <div v-if="item['news_title_image']" style="max-width: 180px;">
                          <img :style="`width: 100%; max-width: ${$vuetify.breakpoint.xs ? '100%;' : '250px;'}`" :src="item['news_title_image']" />
                        </div>
                      </div>
                    </v-col>
                    <v-col cols="12" xs="12" sm="8" md="9" xl="10" style="display: flex; align-items: center;">
                      <div class="text-column pa-3 pt-0 pb-0">
                        <div>
                          <span class="mt-0 mb-1 presentation-card-paragraph" style="font-size: 1.1rem; line-height: 1.5rem; padding-bottom: 2px; display: inline-block;">{{ item.title}}</span>
                        </div>
                        <div class="text" v-html="item.content" style="position: relaitve">
                        </div>
                        <span style="letter-spacing: 0.065rem; font-size: 0.78rem; color: black; line-height: 2rem;">
                          {{`${item.updated_at.substring(0,10)}  ${item.updated_at.substring(12,19)}`}}
                        </span>
                      </div>
                      <div class="material-btn-actions" style="position: absolute; right: 1rem; top: 0.2rem;">
                        <v-btn 
                          @click.stop="editNews(item, item.id)" 
                          icon>
                          <v-icon  size="29" color="green" v-text="'mdi-square-edit-outline'"></v-icon>
                        </v-btn>
                        <v-btn 
                          @click.stop="openDialogToDeleteNews(item.id, item)" 
                          icon>
                          <v-icon size="29" color="#e65048" v-text="'mdi-delete-forever'"></v-icon>
                        </v-btn>
                      </div>
                    </v-col>
                </v-col>
              </v-row>
            </v-card>
          </v-hover>
        </v-card-text>
        <!-- <v-btn @click="test()">test</v-btn> -->
      </section>
    </v-card>
  </div>
  <router-view></router-view>
</div>
</template>

<script>
import axios from 'axios'

import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import BreadsCrumb from '../../../../components/agent-cabinet/components/breadScrumb.vue'
// import EditMaterials from './EditMaterials.vue'

export default {
  mixins: [validationMixin],
  components: {
    BreadsCrumb,
    // EditMaterials
  },
  data: () => ({
    commonErr: ['Обов`язкове поле'],
    materials: [],
    loading: false,

    deleteNewsDialog: false,

    newsToDelete: {
      name: null,
      id: null
    },
    newCategorie: null,
  }),
  validations() {
    return this.validationRules
  },
  computed: {
    validationRules() {
      let validateObj = {}

      this.makeCategoryDialog
        ? validateObj = { newCategorie: { required } }
        : false

      return validateObj
    },
  },
  methods: {
    editNews(material, id) {
      this.$router.push({
        name: 'edit-news', 
        params: {
            edit: true, 
            material: material,
            id: id
        }
      })
    },
    openDialogToDeleteNews(id, materialObj) {
      this.newsToDelete.name = materialObj.title
      this.newsToDelete.id = id
      this.deleteNewsDialog = true
    },
    deleteNews() {
      this.loading = true
      axios
        .delete(`/admin/news/delete/${this.newsToDelete.id}`)
        .then(response => {
          this.loading = false
          this.$notify({
            group: 'success',
            title: `Успiшно - ${response.status}`,
            text: ``,
          })
          this.getNews()
          setTimeout(() => {
            this.deleteNewsDialog = false
          }, 800)
        })
        .catch(error => {
          console.log(error.response)
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
          this.loading = false
        })
    },
    filterCategoryToEditMaterial(categoryId) {
      let categorieObj = this.categories
        .filter(v => { return v.id === categoryId})
      return categorieObj
    },
    changeActive(event) {
      let tabs = document.querySelectorAll('#section .tabs-input')
      tabs.forEach(element => element.classList.remove('active'))
      event.target.parentNode.classList.add('active')
    },
    getNews() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/admin/news/all')
        .then(response => {
          this.materials = response.data
          this.$store.commit('toggleAdminSpinner', false)
        })
        .catch(error => {
          console.log(error.reponse)
          this.$store.commit('toggleAdminSpinner', false)
        })
    },
    removeActiveClass() {
      let activeEl = document.querySelector('#categories-list .list-element.active')
      if (activeEl !== null) {
        activeEl.classList.remove('active')
      }
    },
    getCsrf() {
			return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
  },
  created() {
    this.getNews()
  }
}
</script>

<style scoped>
.text >>> * {
  display: none;
}
.text >>> p:first-of-type {
  padding: 0;
  margin: 0;
  max-height: 130px;
  max-width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}
</style>
<style lang="scss" scoped>
  .admin-categories {
    #categories-list {
      padding: 15px 0;
    }
    .edit-title {
      letter-spacing: 0.1rem; 
      // text-decoration: underline; 
      font-size: 1.8rem; 
      font-weight: bold;
    }
    .edit-subtitle {
      font-size: 1.2rem;
      text-decoration: none;
      font-weight: bold;
      text-decoration: underline;
    }
    .material-card {
      cursor: pointer; 
      position:relative;
      .material-btn-actions {
        transition: opacity 0.3s ease;
        transition-delay: 0.1s;
        opacity: 0;
      }
      &:hover {
        .material-btn-actions {
          opacity: 1;
        }
      }
    }
    .row.material-card {
      min-height: 0!important;
    }
    .list-element {
      list-style: none;
      font-size: 1.4rem;
      line-height: 2.8rem;
      cursor: pointer;
      transition: color 0.3s ease;
      .btn-actions {
        transition: opacity 0.3s ease;
        transition-delay: 0.1s;
        opacity: 0;
      }
      &:before {
        transition: opacity 0.3s ease;
        opacity: 0;
        display: inline-block;
        left: -246px;
        content : "\25B2";
        color: red;
        transform: rotate(90deg);
      }
      &:hover {
        color: black;
      }
      &.active {
        color: red;
        .btn-actions {
          opacity: 1;
        }
        &:before {
          opacity: 1;
        }
      }
    }
  }

  /* Tabs */
  html, body {
    padding: 0;
    margin: 0;
  }
  .section {
    width: 100%;
    min-height: 100vh;
    overflow-x: hidden;
  }
  .section .tabs {
    display: flex;
    position: relative;
  }
  .tabs .tabs-input input,
  .tabs .tabs-input input:focus {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    display: block;
    width: 100%;
    outline: none;
  }
  .tabs .tabs-input [type="radio"] {
    display: block;
    padding: 0;
    margin: 0;
    border-bottom: 1px solid rgba(239, 237, 239, 0.5);
    transition: border-bottom 0.3s ease;
  }
  .tabs .tabs-input [type="radio"]:checked {
    border-bottom: 2px solid #ef5350;
  }
  .tabs .tabs-input [type="radio"] {
    border-bottom: 2px solid rgb(209, 207, 207);
  }
  .tabs .tabs-input .label {
    cursor: pointer;
    display: block;
    width: 100%;
    height: 100%;
    font-size: 26px;
    text-align: center;
    line-height: 2rem;
    color: #212121;
  }
  .tabs .tabs-input {
    width: 50%;
    display: block;
    height: 100%;
    opacity: 0.4;
    transition: opacity 0.3s ease;
  }
  .tabs .tabs-input.active,
  .tabs .tabs-input:hover {
    opacity: 1;
  }
  .tabs-input [type="radio"] + .content {
    display: block;
    opacity: 0;
    padding: 0.5rem 0;
    width: 100%;
    position: absolute;
    left: 100%;
    transition: left 0.3s ease-in-out, opacity 0.3s ease; 
  }
  .tabs-input [type="radio"]:checked + .content {
    left: 0;
    opacity: 1;
  }
</style>