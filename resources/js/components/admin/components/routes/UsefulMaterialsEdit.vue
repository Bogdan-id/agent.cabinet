<template>
  <v-card class="admin-categories">
    <v-dialog
      :max-width="430"
      v-model="editCategoryDialog">
      <v-card>
        <v-card-title class="success white--text">Редагування</v-card-title>
        <v-card-title>Поточна назва -&nbsp; 
          <span style="color: black;">{{ categoryToEdit}}</span>
        </v-card-title>
        <v-card-text>
          <v-text-field
            v-model="categoryToEdit"
            label="Редагувати категорiю"
            outlined>
          </v-text-field>
          <div class="d-flex justify-space-between">
            <span>
              <v-btn
                @click=""
                class="grey darken-3 white--text">
                Зберегти
              </v-btn>
            </span>
            <span>
              <v-btn 
                @click="editCategoryDialog = false"
                class="grey darken-3 white--text">
                Вiдмiнити
              </v-btn>
            </span>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog
      :max-width="430"
      v-model="makeCategoryDialog">
      <v-card>
        <v-card-title class="success white--text">
          Додати категорiю
        </v-card-title>
        <v-card-text class="mt-5">
          <v-text-field
            v-model="newCategorie"
            label="Додати категорiю">
          </v-text-field>
          <div class="d-flex justify-space-between">
            <span>
              <v-btn
                @click=""
                class="grey darken-3 white--text">
                Зберегти
              </v-btn>
            </span>
            <span>
              <v-btn 
                @click="makeCategoryDialog = false"
                class="grey darken-3 white--text">
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
        <v-card-title class="success white--text">
          Видалення
        </v-card-title>
        <v-card-title>
          Категорiя - <b>&nbsp;"{{ categoryToDelete }}"</b>
        </v-card-title>
        <v-card-text class="d-flex justify-space-around">
          <span>
            <v-btn class="grey darken-3 white--text">Так</v-btn>
          </span>
          <span>
            <v-btn 
              @click="deleteCategoryDialog = false" 
              class="grey darken-3 white--text">
              Вiдмiнити
            </v-btn>
          </span>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card-title>
      Роздiл - Кориснi матерiали
    </v-card-title>
    <v-card-text v-if="categories.length > 0">
      <v-row>
        <v-col 
          cols="12" xs="12" sm="12" md="6">
          <v-card-title class="edit-title">Редагувати категорiї</v-card-title>
          <!-- <v-divider class="pl-8 pr-8"></v-divider> -->
          <v-btn @click="makeCategoryDialog = true" class="ml-6 red lighten-1" dark>
            Додати категорiю&nbsp;
            <v-icon v-text="'mdi-plus'"></v-icon>
          </v-btn>
          <ul id="categories-list">
            <li
              v-for="item in categories"
              :key="item.id"
              class="list-element"
              @click.self="makeActive($event, item.name)">
              {{ item.name }}
              <span class="btn-actions">
                <v-btn 
                  @click.stop="editCategoryDialog = true; 
                    editCategory(item.id, item.name)" 
                  icon>
                  <v-icon color="green" v-text="'mdi-square-edit-outline'"></v-icon>
                </v-btn>
                <v-btn 
                  @click.stop="deleteCategoryDialog = true;
                    deleteCategory(item.id, item.name)" 
                  icon>
                  <v-icon color="red" v-text="'mdi-delete-forever'"></v-icon>
                </v-btn>
              </span>
            </li>
          </ul>
        </v-col>
        <!-- <v-col 
          v-if="categoryToEdit !== null" 
          cols="12" xs="12" sm="12" md="6">
          <v-card-title class="edit-subtitle">Редагувати матерiали в категорiї -&nbsp;<i>{{ categoryToEdit }}</i></v-card-title>
          <v-btn @click="makeCategory()" class="ml-6 red lighten-1" dark>
            Додати матерiал&nbsp;
            <v-icon v-text="'mdi-plus'"></v-icon>
          </v-btn>
          <ul id="categories-list">
            <li
              v-for="item in categories"
              :key="item.id"
              class="list-element"
              @click.self="makeActive($event, item.name)">
              {{ item.name }}
              <span class="btn-actions">
                <v-btn 
                  @click.stop="editCategoryDialog = true; 
                    editCategory(item.id, item.name)" 
                  icon>
                  <v-icon color="green" v-text="'mdi-square-edit-outline'"></v-icon>
                </v-btn>
                <v-btn 
                  @click.stop="deleteCategoryDialog = true;
                    deleteCategory(item.id, item.name)" 
                  icon>
                  <v-icon color="red" v-text="'mdi-delete-forever'"></v-icon>
                </v-btn>
              </span>
            </li>
          </ul>
        </v-col> -->
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    categories: [],

    editCategoryDialog: false,
    deleteCategoryDialog: false,
    makeCategoryDialog: false,

    categoryToEdit: null,
    categoryToDelete: null,
    newCategorie: null,
  }),
  methods: {
    getMageterialCategories() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
        .get('/useful-materials-categories/all')
        .then(response => {
          console.log(response)
          this.categories = response.data
          this.$store.commit('toggleAdminSpinner', false)
        })
        .catch(error => {
          console.log(error.response)
          this.$store.commit('toggleAdminSpinner', false)
        })
    },
    makeActive(e, category) {
      console.log(e.target)
      this.categoryToEdit = category
      document.querySelectorAll('#categories-list .btn-actions button')
        .forEach(button => button.disabled = true)
      let activeEl = document.querySelector('#categories-list .list-element.active')
      if (activeEl !== null) {
        activeEl.classList.remove('active')
      }
      e.target.classList.add('active')
      document.querySelectorAll('#categories-list .list-element.active .btn-actions button')
        .forEach(button => button.disabled = false)
    },
    editCategory(id, category) {
      this.categoryToEdit = category
    },
    deleteCategory(id, category) { 
      this.categoryToDelete = category
    },
    makeCategory() {
      /*  */
    }
  },
  created() {
    this.getMageterialCategories()
  }
}
</script>

<style lang="scss">
  .admin-categories {
    #categories-list {
      padding: 15px 0;
    }
    .edit-title {
      letter-spacing: 0.1rem; 
      text-decoration: underline; 
      font-size: 1.8rem; 
      font-weight: bold;
    }
    .edit-subtitle {
      font-size: 1.2rem;
      text-decoration: none;
      font-weight: bold;
      text-decoration: underline;
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
</style>