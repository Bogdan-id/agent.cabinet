<template>
<div>
  <breads-crumb />
  <div v-show="$route.name === 'Роздiл Кориснi матерiали'">
    <v-card class="admin-categories">
      <v-dialog
        :max-width="430"
        v-model="editCategoryDialog">
        <v-card>
          <v-card-title class="white--text grey darken-4">
            <v-icon large color="white"  class="mr-2" v-text="'mdi-information'"></v-icon>
            Редагування
          </v-card-title>
          <v-card-title class="mb-4">Поточна назва -&nbsp; 
            <span style="color: black;">"{{ categoryToEdit.name}}"</span>
          </v-card-title>
          <v-card-text>
            <v-text-field
              @input="editCategoryDialog ? $v.categoryToEdit.name.$touch() : ''"
              @blur="editCategoryDialog ? $v.categoryToEdit.name.$touch() : ''"
              :error-messages="categoryToEditErr"
              v-model="categoryToEdit.name"
              label="Редагувати категорiю"
              outlined>
            </v-text-field>
            <v-card-actions>
              <v-btn
                @click="saveChangedCategory()"
                :loading="loading"
                color="error">
                Зберегти
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn 
                @click="editCategoryDialog = false"
                class="grey darken-2 white--text">
                Вiдмiнити
              </v-btn>
            </v-card-actions>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog
        :max-width="430"
        v-model="makeCategoryDialog">
        <v-card>
          <v-card-title class="white--text grey darken-4 mb-8">
            <v-icon large class="mr-2" color="white" v-text="'mdi-information'"></v-icon>
            Додати категорiю
          </v-card-title>
          <v-card-text>
            <span>
              Введiть назву категорiї
            </span>
            <v-text-field
              @input="makeCategoryDialog ? $v.newCategorie.$touch() : ''"
              @blur="makeCategoryDialog ? $v.newCategorie.$touch() : ''"
              :error-messages="newCategorieErr"
              v-model="newCategorie"
              label="Додати категорiю" outlined>
            </v-text-field>
            <div class="d-flex justify-space-between">
              <span>
                <v-btn
                  @click="makeCategory()"
                  :loading="loading"
                  color="error">
                  Зберегти
                </v-btn>
              </span>
              <span>
                <v-btn 
                  @click="makeCategoryDialog = false"
                  class="grey darken-2 white--text">
                  Вiдмiнити
                </v-btn>
              </span>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog
        :max-width="430"
        v-model="deleteCategoryDialog">
        <v-card>
          <v-card-title class="white--text grey darken-4">
            <v-icon large class="mr-2" color="white" v-text="'mdi-information'"></v-icon>
            Видалення
          </v-card-title>
          <v-card-title class="mt-4">
            Категорiю -<b class="pr-1">"{{ categoryToDelete.name }}"</b>
            буде видалено назавжди.&nbsp;
            <span style="white-space: pre-line; font-weight: bold;">Продовжити?</span>
          </v-card-title>
          <v-card-text class="pt-4">
            <div class="d-flex justify-space-between">
              <span>
                <v-btn 
                  @click="deleteCategory()"
                  :loading="loading"
                  color="error">
                  Так
                </v-btn>
              </span>
              <span>
                <v-btn 
                  @click="deleteCategoryDialog = false" 
                  class="grey darken-2 white--text">
                  Hi
                </v-btn>
              </span>
            </div>
        </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog
        :max-width="430"
        v-model="deleteMaterialDialog">
        <v-card>
          <v-card-title class="white--text grey darken-4">
            <v-icon large class="mr-2" color="white" v-text="'mdi-information'"></v-icon>
            Видалення матерiалу
          </v-card-title>
          <v-card-title class="mt-4">
            Матерiал -<b class="pr-1" style="white-space: pre;">"{{ materialToDelete.name }}"</b>
            буде видалено назавжди.&nbsp;
            <span style="white-space: pre-line; font-weight: bold;">Продовжити?</span>
          </v-card-title>
          <v-card-text class="pt-4">
            <div class="d-flex justify-space-between">
              <span>
                <v-btn 
                  @click="deleteMaterial()"
                  :loading="loading"
                  color="error">
                  Так
                </v-btn>
              </span>
              <span>
                <v-btn 
                  @click="deleteMaterialDialog = false" 
                  class="grey darken-2 white--text">
                  Hi
                </v-btn>
              </span>
            </div>
        </v-card-text>
        </v-card>
      </v-dialog>
      <v-card-title>
        Роздiл - Кориснi матерiали
      </v-card-title>
      <section class="section" id="section">
        <div class="tabs">
          <div class="tabs-input active">
            <label
              @click.stop="changeActive($event)" 
              for="tab-1" 
              class="label">
              <v-icon v-text="'mdi-format-list-bulleted-square'" class="pr-1" large></v-icon>
              Категорії
            </label>
            <input 
              @click.stop="changeActive($event)"
              id="tab-1" 
              name="tab" 
              type="radio" 
              value="1" 
              checked="checked" 
              v-model="currentTab"/>
            <div class="content">
              <v-card-text v-if="$store.state.adminLoader">
                <v-progress-circular
                  indeterminate
                  color="red">
                </v-progress-circular>
              </v-card-text>
              <v-card-text>
                <v-card-title class="edit-title pb-6">Редагувати категорiї</v-card-title>
                <!-- <v-divider class="pl-8 pr-8"></v-divider> -->
                <v-btn 
                  @click="makeCategoryDialog = true; 
                    removeActiveClass()" 
                  class="ml-6 red lighten-1" dark>
                  Додати категорiю&nbsp;
                  <v-icon v-text="'mdi-plus'"></v-icon>
                </v-btn>
                <ul id="categories-list" v-if="categories.length > 0">
                  <li
                    v-for="item in categories"
                    :key="item.id"
                    class="list-element"
                    @click.self="makeActiveCategory($event, item.name)">
                    {{ item.name }}
                    <span class="btn-actions">
                      <v-btn 
                        @click.stop="openDialogToEditCategory(item.id, item.name)" 
                        icon>
                        <v-icon color="green" v-text="'mdi-square-edit-outline'"></v-icon>
                      </v-btn>
                      <v-btn 
                        @click.stop="openDialogDeleteCategory(item.id, item.name)" 
                        icon>
                        <v-icon color="#e65048" v-text="'mdi-delete-forever'"></v-icon>
                      </v-btn>
                    </span>
                  </li>
                </ul>
              </v-card-text>
            </div>
          </div>
          <div class="tabs-input">
            <label 
              @click.stop="changeActive($event)" 
              for="tab-2" 
              class="label">
              <v-icon v-text="'mdi-playlist-edit'" class="pr-1" large></v-icon>
              Матерiали
            </label>
            <input
              @click.stop="changeActive($event)"
              id="tab-2" 
              name="tab" 
              type="radio" 
              value="2"
              v-model="currentTab">
            <div class="content">
              <v-card-text>
                <v-card-title class="edit-title">
                  Редагувати матерiали
                  <v-spacer></v-spacer>
                  <v-select
                    style="max-width: 220px!important;"
                    @change="filterByCategory($event)"
                    :items="categories"
                    v-model="selectedCategorie"
                    item-text="name"
                    item-value="id"
                    label="Фiльтр по категорiям"> 
                    <template v-slot:prepend-item>
                      <v-list class="pt-0 pb-0">
                        <v-list-item @click="showMaterialCategorie()">
                          <v-list-item-content>
                            ВСI МАТЕРIАЛИ
                          </v-list-item-content>
                        </v-list-item>
                      </v-list>
                    </template>
                  </v-select>
                </v-card-title>
                <v-row>
                  <v-col cols="12">
                    <v-btn 
                      :to="{name: 'Новый матерiал', params: {categories}}"
                      class="red lighten-1" dark>
                      Додати матерiал&nbsp;
                      <v-icon v-text="'mdi-plus'"></v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
                <v-hover 
                  v-for="(item, key) in filteredMaterials"
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
                              <div style="max-width: 180px;">
                                <img :style="`width: 100%; max-width: ${$vuetify.breakpoint.xs ? '100%;' : '250px;'}`" :src="item.title_image || emptyTitleImg" />
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
                                {{`${$formatDate(item.updated_at)}  ${item.updated_at.substring(11,19)}`}}
                              </span>
                            </div>
                            <div class="material-btn-actions" style="position: absolute; right: 1rem; top: 0.2rem;">
                              <v-btn 
                                @click.stop="editMaterial(item.id, item)" 
                                icon>
                                <v-icon  size="29" color="green" v-text="'mdi-square-edit-outline'"></v-icon>
                              </v-btn>
                              <v-btn 
                                @click.stop="openDialogToDeleteMaterial(item.id, item)" 
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
            </div>
          </div>
        </div>
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
    emptyTitleImg: require('../../../../assets/images/empty-title-img.jpeg'),

    commonErr: ['Обов`язкове поле'],
    categories: [],
    materials: [],
    filteredMaterials: [],
    loading: false,
    currentTab: '1',
    selectedCategorie: 'Всi матерiали',

    editCategoryDialog: false,
    deleteCategoryDialog: false,
    makeCategoryDialog: false,
    deleteMaterialDialog: false,

    materialToDelete: {
      name: null,
      id: null
    },
    categoryToEdit: {
      id: null,
      name: null
    },
    categoryToDelete: {
      id: null,
      name: null
    },
    newCategorie: null,
  }),
  validations() {
    return this.validationRules
  },
  computed: {
    validationRules() {
      let validateObj = {}
      this.editCategoryDialog
        ? validateObj = { categoryToEdit: { name: { required } } }
        : false

      this.makeCategoryDialog
        ? validateObj = { newCategorie: { required } }
        : false
      return validateObj
    },
    categoryToEditErr() {
      if (!this.editCategoryDialog) return
      if (!this.$v.categoryToEdit.name.$error) return
      return this.commonErr
    },
    newCategorieErr() {
      if(!this.makeCategoryDialog) return
      if (!this.$v.newCategorie.$error) return
      return this.commonErr
    },
  },
  methods: {
    editMaterial(id, material) {
      let category = this.filterCategoryToEditMaterial(material.useful_materials_category_id)
      this.$router.push({
        name: 'edit-material', 
        params: {
            edit: true, 
            category: category, 
            material: material,
            id: id
        }
      })
    },
    openDialogToDeleteMaterial(id, materialObj) {
      this.materialToDelete.name = materialObj.title
      this.materialToDelete.id = id
      this.deleteMaterialDialog = true
    },
    deleteMaterial() {
      this.loading = true
      axios
        .delete(`/admin/useful-material/delete/${this.materialToDelete.id}`)
        .then(response => {
          this.loading = false
          this.$notify({
            group: 'success',
            title: `Успiшно - ${response.status}`,
            text: ``,
          })
          this.getUsefulMaterials()
          setTimeout(() => {
            this.deleteMaterialDialog = false
          }, 800)
        })
        .catch(error => {
          this.loading = false
          this.$catchStatus(error.response.status, error)
        })
    },
    showMaterialCategorie() {
      this.filteredMaterials = this.materials
      this.selectedCategorie = null
      let el = document.getElementById('app')
      let clickEvent = new Event('click', {bubbles: true})
      el.dispatchEvent(clickEvent)
    },
    filterCategoryToEditMaterial(categoryId) {
      let categorieObj = this.categories
        .filter(v => { return v.id === categoryId})
      return categorieObj
    },
    filterByCategory(id) {
      this.filteredMaterials = this.materials
        .filter(v => { return v.useful_materials_category_id === id })
    },
    changeActive(event) {
      let tabs = document.querySelectorAll('#section .tabs-input')
      tabs.forEach(element => element.classList.remove('active'))
      event.target.parentNode.classList.add('active')
    },
    getMageterialCategories() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/useful-materials-categories/all')
        .then(response => {
          this.categories = response.data
          this.$store.commit('toggleAdminSpinner', false)
          this.getUsefulMaterials()
        })
        .catch(error => {
          this.$catchStatus(error.response.status, error)
          this.$store.commit('toggleAdminSpinner', false)
          this.getUsefulMaterials()
        })
    },
    getUsefulMaterials() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/admin/useful-materials/all')
        .then(response => {
          this.materials = response.data
          this.filteredMaterials = response.data
          this.$store.commit('toggleAdminSpinner', false)
        })
        .catch(error => {
          this.$catchStatus(error.response.status, error)
          this.$store.commit('toggleAdminSpinner', false)
        })
    },
    makeActiveCategory(e, category) {
      this.categoryToEdit.name = category
      document.querySelectorAll('#categories-list .btn-actions button')
        .forEach(button => button.disabled = true)
      this.removeActiveClass()
      e.target.classList.add('active')
      document.querySelectorAll('#categories-list .list-element.active .btn-actions button')
        .forEach(button => button.disabled = false)
    },
    removeActiveClass() {
      let activeEl = document.querySelector('#categories-list .list-element.active')
      if (activeEl !== null) {
        activeEl.classList.remove('active')
      }
    },
    openDialogToEditCategory(id, category) {
      this.editCategoryDialog = true
      this.categoryToEdit.name = category
      this.categoryToEdit.id = id
    },
    openDialogDeleteCategory(id, category) {
      this.deleteCategoryDialog = true
      this.categoryToDelete.name = category
      this.categoryToDelete.id = id
    },
    deleteCategory() {
      this.loading = true
      axios
        .delete(`/admin/useful-materials-categories/delete/${this.categoryToDelete.id}`, {_token: this.getCsrf()})
        .then(() => {
          this.loading = false
          this.getMageterialCategories()
          this.$notify({
            group: 'success',
            title: 'Успiшно',
            text: ``,
          })
          setTimeout(() => {
            this.deleteCategoryDialog = false
          }, 800)
        })
        .catch(error => {
          this.$catchStatus(error.response.status, error)
          this.loading = false
        })
    },
    makeCategory() {
      if(this.$v.$dirty && !this.$v.$invalid) {
        this.loading = true
        axios
          .post(`/admin/useful-materials-categories/create`, 
            {
              name: this.newCategorie,
              _token: this.getCsrf()
            }
          ).then(() => {
            this.loading = false
            this.getMageterialCategories()
            this.$notify({
              group: 'success',
              title: 'Успiшно',
              text: ``,
            })
            setTimeout(() => {
              this.makeCategoryDialog = false
              this.newCategorie = null
            }, 800)
          })
          .catch(error => {
            this.$catchStatus(error.response.status, error)
            this.loading = false
          })
      } else this.highlightErrors()
    },
    saveChangedCategory() {
      if(this.$v.$dirty && !this.$v.$invalid) {
        this.loading = true
        axios
          .post(`/admin/useful-materials-categories/update/${parseInt(this.categoryToEdit.id)}`, 
            {
              name: this.categoryToEdit.name,
              _token: this.getCsrf()
            }
          ).then(() => {
            this.loading = false
            this.getMageterialCategories()
            this.$notify({
              group: 'success',
              title: 'Успiшно',
              text: ``,
            })
            setTimeout(() => {
              this.editCategoryDialog = false
            }, 800)
          })
          .catch(error => {
            this.$catchStatus(error.response.status, error)
            this.loading = false
          })
      } else this.highlightErrors()
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
    this.getMageterialCategories()
  },
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