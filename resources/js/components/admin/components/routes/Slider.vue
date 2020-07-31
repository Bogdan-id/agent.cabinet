<template>
  <v-card>
    <v-dialog
      v-model="dialogToDelete"
      max-width="390">
      <v-card>
        <v-card-title style="background: #424242; position: relative" class="white--text">
          Пiдтвердження
          <v-btn @click="dialogToDelete = false" style="position: absolute; right: 4px; top: 6px;" icon><v-icon v-text="'mdi-close'" color="white"></v-icon></v-btn>
        </v-card-title>
        <div style="padding: 25px; font-size: 1.23rem; text-align: center;">
          <span>Слайдер буде видалено назавжди. Продавжити?</span>
        </div>
        <v-card-text>
          <div style="display: flex; justify-content: space-between;">
            <span><v-btn @click="deleteSlider()" dark color="red lighten-1" :loading="deleteLoading">Так</v-btn></span>
            <span><v-btn @click="dialogToDelete = false" dark color="grey darken-2">Нi</v-btn></span>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card-title>
      Роздiл - Слайдер
    </v-card-title>
    <v-card-text>
      <v-btn
        v-show="!editMode"
        @click="addSlider()" 
        color="#e85d56"
        dark>
        Додати слайдер
      </v-btn>
    </v-card-text>
    <v-card-text v-if="$store.state.adminLoader">
      <v-progress-circular
        color="red"
        indeterminate>
      </v-progress-circular>
    </v-card-text>
    <v-card-text>
      <input
        style="visibility: hidden; position: absolute; left: 0; top: 0;"
        type="file"
        accept="image/x-png, image/gif, image/jpeg, image/jpg, image/svg+xml"
        ref="sliderImageInput"
        class="slider-image-input"
        @change="listenImageInput()">
      <div v-show="formToAddSlider" class="mb-8">
        <v-card
          :style="`margin: 0 auto; position: relative; background: url('${sliderImage}'); background-size: cover;`"
          class="slider-card"
          max-width="900"
          height="400" 
          elevation="8">
          <!-- v-if="sliderImageErr.length > 0" -->
          <span v-show="sliderImageErr && sliderImageErr.length > 0" style="font-size: 1.7rem; position: absolute; bottom: 100px; right: 50%; color: rgb(230, 80, 72); transform: translate(50%, -50%);">
            Додайте зображення!
          </span>
          <div style="height: 120px;">
            <div v-show="sliderImage !== null" :class="$vuetify.breakpoint.xs ? 'actions-block-text small-screen' : 'actions-block-text'">
              <h3><b>{{ sliderTitle || 'Заголовок матерiалу' }}</b></h3>
              <p style="font-size: 0.88rem"> {{ sliderDescription || 'Контент матерiалу' }} </p>
            </div>
          </div>
          <div style="height: 240px; display: flex; width: 100%; justify-content: flex-end; align-items: flex-end;">
            <span v-show="sliderImage !== null" style="display: inline-block; margin-right: 40px; margin-bottom: 15px">
              <v-btn class="vuetify_custom-btn white--text ml-1 mt-7" @click="">Ознайомитись</v-btn>
            </span>
          </div>
          <span v-show="sliderImage === null" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn
                  @click="addImageToSlider()"  
                  v-on="on" 
                  width="90" 
                  height="90" 
                  x-large 
                  icon>
                  <v-icon size="58" v-text="'mdi-image-plus'"></v-icon>
                </v-btn>
              </template>
              <span>Додати зображення</span>
            </v-tooltip>
          </span>
          <span class="slider-delete-image-icon" v-show="sliderImage" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); z-index: 25;">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn
                  @click="removeImageSlider()"  
                  v-on="on" 
                  width="90" 
                  height="90" 
                  x-large 
                  icon>
                  <!-- <v-icon size="58" v-text="'mdi-image-plus'"></v-icon> -->
                  <v-icon size="58" v-text="'mdi-close-circle'"></v-icon>
                </v-btn>
              </template>
              <span>Видалити зображення</span>
          </v-tooltip>
          </span>
        </v-card>
        <v-card
          max-width="900"
          min-height="120"
          elevation="10"
          class="mt-4"
          style="margin: 0 auto; position: relative;">
          <div class="triangle-up"></div>
          <v-card-text>
            <div>
              <span>Заголовок</span>
            </div>
            <v-text-field
              @input="$v.sliderTitle.$touch()"
              @blur="$v.sliderTitle.$touch()"
              v-model="sliderTitle"
              :error-messages="sliderTitleErr"
              outlined dense>
            </v-text-field>
            <div>
              <span>Короткий опис</span>
            </div>
            <v-textarea
              @input="$v.sliderDescription.$touch()"
              @blur="$v.sliderDescription.$touch()"
              v-model="sliderDescription"
              :error-messages="sliderDescriptionErr"
              class="slider-textarea"
              rows="3"
              outlined>
            </v-textarea>
            <ckeditor
              v-if="formToAddSlider"
              :editor="editor" v-model="editorData" :config="editorConfig">
            </ckeditor>
            <div class="d-flex justify-space-between mt-5">
              <span>
                <v-btn 
                  @click="createSlider(); formToAddSlider = false" 
                  color="#e65048"
                  dark
                  :loading="saveLoading">
                  Зберегти
                </v-btn>
              </span>
              <span>
                <v-btn 
                  @click="stopEditMode(); 
                    formToAddSlider = false" 
                  color="grey"
                  dark>
                  Вiдмiнити
                </v-btn>
              </span>
            </div>
          </v-card-text>
        </v-card>
      </div>

      <div
        v-for="(item, key) in sliders"
        class="mb-12"
        :key="key">
        <v-hover v-slot:default="{ hover }">
          <v-card
            :style="`margin: 0 auto; position: relative; background: url('${item.slide_image || sliderImage}'); background-size: cover;`"
            class="slider-card"
            max-width="900"
            height="400" 
            :elevation="hover ? 12 : 4">
            <v-btn
              v-show="!formToAddSlider && !editMode"
              class="slider-edit-button"
              @click="activateEditMode(item, key)"
              style="position: absolute; right: 0; top: 0;"
              dark 
              small
              color="grey darken-2">
              Редагувати
            </v-btn>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn
                  v-show="!formToAddSlider && !editMode"
                  v-on="on"
                  class="slider-edit-button"
                  @click="activateEditMode(item, key, true, item.id);"
                  fab
                  x-small
                  color="red lighten-1"
                  style="position: absolute; right: 132px; top: 0px;">
                  <v-icon v-text="'mdi-close'"></v-icon>
                </v-btn>
              </template>
              <span>Видалити слайдер</span>
            </v-tooltip>
            <span v-show="sliderImageErr && sliderImageErr.length > 0 && editKey === key" style="font-size: 1.7rem; position: absolute; bottom: 100px; right: 50%; color: rgb(230, 80, 72); transform: translate(50%, -50%);">
              Додайте зображення!
            </span>
            <div style="height: 120px;">
              <div v-show="item.slide_image || sliderImage !== null" :class="$vuetify.breakpoint.xs ? 'actions-block-text small-screen' : 'actions-block-text'">
                <h3><b>{{ item.title || sliderTitle || 'Заголовок матерiалу' }}</b></h3>
                <p style="font-size: 0.88rem"> {{ item.description ||  sliderDescription || 'Короткый опис матерiалу' }} </p>
              </div>
            </div>
            <div style="height: 240px; display: flex; width: 100%; justify-content: flex-end; align-items: flex-end;">
              <span v-show="item.slide_image || sliderImage !== null" style="display: inline-block; margin-right: 40px; margin-bottom: 15px">
                <v-btn class="vuetify_custom-btn white--text ml-1 mt-7" @click="">Ознайомитись</v-btn>
              </span>
            </div>
            <span v-show="editMode && editKey == key && !item.slide_image && sliderImage === null" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-btn
                    @click="addImageToSlider()"  
                    v-on="on" 
                    width="90" 
                    height="90" 
                    x-large 
                    icon>
                    <v-icon size="58" v-text="'mdi-image-plus'"></v-icon>
                  </v-btn>
                </template>
                <span>Додати зображення</span>
              </v-tooltip>
            </span>
            <span class="slider-delete-image-icon" v-show="editMode && editKey == key && item.slide_image" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); z-index: 25;">
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-btn
                    @click="removeImageSlider(key)"  
                    v-on="on" 
                    width="90" 
                    height="90" 
                    x-large 
                    icon>
                    <v-icon size="58" v-text="'mdi-close-circle'"></v-icon>
                  </v-btn>
                </template>
                <span>Видалити зображення</span>
              </v-tooltip>
            </span>
          </v-card>
        </v-hover>
        <v-card
          v-show="editMode && editKey == key"
          max-width="900"
          min-height="120"
          elevation="10"
          class="mt-4"
          style="margin: 0 auto; position: relative;">
          <div class="triangle-up"></div>
          <v-card-text>
            <div>
              <span>Заголовок</span>
            </div>
            <v-text-field
              @input="$v.sliderTitle.$touch()"
              @blur="$v.sliderTitle.$touch()"
              v-model="sliderTitle"
              :error-messages="sliderTitleErr"
              outlined dense>
            </v-text-field>
            <div>
              <span>Короткий опис</span>
            </div>
            <v-textarea
              @input="$v.sliderDescription.$touch()"
              @blur="$v.sliderDescription.$touch()"
              v-model="sliderDescription"
              :error-messages="sliderDescriptionErr"
              class="slider-textarea"
              rows="3"
              outlined>
            </v-textarea>
            <ckeditor
              v-if="!formToAddSlider && editMode && editKey === key"
              :editor="editor" v-model="editorData" :config="editorConfig">
            </ckeditor>
            <div class="d-flex justify-space-between mt-5">
              <span>
                <v-btn 
                  @click="createSlider(item.id); formToAddSlider = false" 
                  color="#e65048"
                  dark
                  :loading="saveLoading">
                  Зберегти
                </v-btn>
              </span>
              <span>
                <v-btn 
                  @click="stopEditMode(); formToAddSlider = false" 
                  color="grey"
                  dark>
                  Вiдмiнити
                </v-btn>
              </span>
            </div>
          </v-card-text>
        </v-card>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'

import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter'
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor'
import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials'
import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold'
import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic'
import LinkPlugin from '@ckeditor/ckeditor5-link/src/link'
import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph'
import Heading from '@ckeditor/ckeditor5-heading/src/heading'
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment'
import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat'
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote'
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption'
import Image from '@ckeditor/ckeditor5-image/src/image'
import ImageUpload from '@ckeditor/ckeditor5-image/src/imageupload'
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle'
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar'
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize'
import List from '@ckeditor/ckeditor5-list/src/list'

import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data: () => ({
    // ckeditor
    editor: ClassicEditor,
      editorConfig: {
      plugins: [ 
        SimpleUploadAdapter,
        EssentialsPlugin,
        BoldPlugin,
        ItalicPlugin,
        LinkPlugin,
        ParagraphPlugin,
        Heading,
        Alignment,
        Autoformat,
        BlockQuote,
        ImageCaption,
        Image,
        ImageUpload,
        ImageStyle,
        ImageToolbar,
        List,
        ImageResize,
        // LinkImage
      ],
      toolbar: {
        items: [
          'heading',
          '|',
          'alignment',
          'bold',
          'italic',
          'link',
          // 'list',
          // 'TodoList',
          'ImageUpload',
          'blockQuote',
          'undo',
          'redo',
        ]
      },
      image: {
        upload: {
          types: ['jpeg', 'png', 'gif', 'jpg', 'svg']
        },
        toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],
        styles: [
              'full',
              'side',
              'alignLeft',
              'alignRight',
              'alignCenter'
          ]
      },
      language: 'uk',
      simpleUpload: {
        uploadUrl: '/admin/image/upload',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      }
    },

    // sliders data
    sliders: null,
    maxSliderValue: 3,

    // image
    sliderImageUrl: null,
    sliderImageName: null,
    sliderImage: null,

    // text-content
    sliderTitle: null,
    sliderDescription: null,
    editorData: '<p>Контент</p>',

    queryParameter: null,
    editKey: null,

    // auxiliary data
    editMode: false,
    saveLoading: false,
    formToAddSlider: false,
    dialogToDelete: false,
    deleteLoading: false,
    commonErr: ['Обов`язкове поле']
  }),
  validations() {
    return {
      sliderImage: (() => {
        if (this.formToAddSlider || this.editMode){
          return { required }
        } else return true
      })(),
      sliderTitle: (() => {
        if (this.formToAddSlider || this.editMode){
          return { required }
        } else return true
      })(),
      sliderDescription: (() => {
        if (this.formToAddSlider || this.editMode){
          return { required }
        } else return true
      })(),
    }
  },
  computed: {
    sliderImageErr() {
      if (!this.$v.sliderImage.$error) return
      return this.commonErr
    },
    sliderTitleErr() {
      if (!this.$v.sliderTitle.$error) return
      return this.commonErr
    },
    sliderDescriptionErr() {
      if (!this.$v.sliderDescription.$error) return
      return this.commonErr
    },
  },
  methods: {
    addSlider() {
      this.$v.$reset()
      if(this.sliders.length === this.maxSliderValue) {
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Досягнуто максимальну кількість дозволених слайдерiв`,
        })
      } else {
        this.formToAddSlider = true
      }
    },
    deleteSlider() {
      this.deleteLoading = true
      axios
        .delete(`/admin/slide/delete/${this.deleteKey}`)
        .then(() => {
          this.deleteLoading = false
          this.$notify({
            group: 'success',
            title: 'Слайдер видалено',
            text: ``,
          })
          this.getSliders()
          this.stopEditMode()
          setTimeout(() => {
            this.dialogToDelete = false
          }, 1200)
        })
        .catch(error => {
          this.deleteLoading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
          this.stopEditMode()
        })
    },
    addImageToSlider() {
      this.$refs.sliderImageInput.click()
    },
    getImageNameFromUrl(key, editKey) {
      let index = this.sliders[key || editKey].slide_image.lastIndexOf('/') + 1
      return this.sliders[key || this.editKey].slide_image.substr(index)
    },
    removeImageSlider(key) {
      let file = document.querySelector('.slider-image-input')
      file.value = null
      if(key !== null && key !== '' && key !== undefined) {
        this.deleteImage(this.getImageNameFromUrl(key, this.editKey))
        this.sliders[key].slide_image = null
      } else {
        this.deleteImage()
      }
    },
    uploadImage(file) {
      let formData = new FormData()
      formData.append('upload', file)
      axios
        .post('/admin/image/upload', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
          let index = response.data.url.lastIndexOf("/") + 1
          this.sliderImageName = response.data.url.substr(index)
          this.sliderImageUrl = response.data.url
          if(this.editKey !== undefined && this.editKey !== null) {
            this.sliders[this.editKey].slide_image = response.data.url
          }
        })
        .catch(error => {
          console.log(error.response)
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
        })
    },
    createSlider(id) {
      if(this.sliders.length === this.maxSliderValue && !id) {
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Досягнуто максимальну кількість дозволених слайдерiв`,
        })
        return
      } else {
      !this.$v.$invalid
        ? this.createSliderRequest(id)
        : this.highlightErrors()
      }
    },
    createSliderRequest(id) {
      let query = '/admin/slide/create'
      if(id) { query = `/admin/slide/update/${id}`}
      this.saveLoading = true
      axios
        .post(query, this.finalObj())
        .then(() => {
          this.saveLoading = false
          this.editMode = false
          this.formToAddSlider = false 
          this.$notify({
            group: 'success',
            title: 'Успiшно',
            text: ``,
          })
          this.getSliders()
          this.stopEditMode()
        })
        .catch(error => {
          console.log(error)
          this.saveLoading = false
          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
          this.getSliders()
          this.stopEditMode()
        })
    },
    activateEditMode(slideData, key, deleteMode, id) {
      this.$v.$reset()
      if(deleteMode) {
        this.deleteKey = id
        this.dialogToDelete = true
      } else {
        this.editKey = key
        this.editMode = true
        this.sliderImage = this.sliders[key].slide_image
        this.sliderImageUrl = this.sliders[key].slide_image
        this.sliderImageName = this.getImageNameFromUrl(key, this.editKey)
        this.sliderTitle = this.sliders[key].title
        this.sliderDescription = this.sliders[key].description
        this.editorData = this.sliders[key].content || '<p></p>'
      }
    },
    stopEditMode() {
      this.editMode = false
      this.editKey = null
      this.sliderImage = null
      this.sliderImageUrl = null
      this.sliderImageName = null
      this.sliderTitle = null
      this.sliderDescription = null
      this.formToAddSlider = false
      this.editorData = '<p>Контент</p>'
    },
    finalObj() {
      return {
        slideImage: this.sliderImageUrl,
        description: this.sliderDescription,
        title: this.sliderTitle,
        content: this.editorData,
        _token: this.getCsrf()
      }
    },
    deleteImage(imageFromArray) {
      axios
        .post('/admin/image/delete', {image: imageFromArray || this.sliderImageName})
        .then(() => {
          this.sliderImage = null
          this.sliderImageUrl = null
          this.sliderImageName = null
          this.sliderImage = null

          let sliderImageInput = document.querySelector('.slider-image-input')
          sliderImageInput.value = null
        })
        .catch(error => {
          console.log(error.response)
        })
    },
    async listenImageInput() {
      let file = document.querySelector('.slider-image-input').files[0]
      let reader = new FileReader()
      this.sliderImageName = file.name
      if(file.size > 2000000) {
        // this.materialImg = null
        file.value = null
        this.$notify({
          group: 'error',
          title: 'Помилка',
          text: `Розмiр зображення не повинен перевищувати 2 mb`,
        })
        return
      } else {
        this.uploadImage(file)
        if (file) {
          reader.readAsDataURL(file)
        }
        await new Promise(resolve => {
          reader.onloadend = () => {
            resolve(reader.result)
          }
        }).then(value => this.sliderImage = value)
      }
    },
    getCsrf() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    highlightErrors() {
      this.$v.$anyError
      this.$v.$touch()
    },
    getSliders() {
      this.$store.commit('toggleAdminSpinner', true)
      axios
      .get('/admin/slide/all')
      .then(response => {
        this.sliders = response.data
        this.$store.commit('toggleAdminSpinner', false)
      })
      .catch(error => {
        console.log(error)
        this.$store.commit('toggleAdminSpinner', false)
      })
    }
  },
  created() {
    this.getSliders()
  }
}
</script>

<style lang="scss">
  .actions-block-text {
    backdrop-filter: blur(5px); 
    padding: 0 15px; 
    border-radius: 3px;
    color: black!important;
    background-color: rgba(252, 252, 252, 0.7); 
    max-width: 48%;
    margin: 25px 0 5px 25px; 
    display: inline-block;
    &.small-screen {
      max-width: 90%;
    }
  }
  .triangle-up {
    position: absolute;
    top:-14px;
    left: 49%;
    right: 51%;
    margin: 0 auto;
    width: 0;
    height: 0;
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    border-bottom: 15px solid white;
  }
  .slider-image-input {
    .v-text-field__details {
      margin-bottom: 0!important;
    }
  }
  .slider-textarea {
    .v-text-field__details {
      margin-bottom: 0!important;
    }
  }
  .slider-delete-image-icon {
    opacity: 0;
    transition: opacity 0.4s ease;
  }
  .slider-edit-button {
    opacity: 0;
  }
  .slider-card:hover .slider-delete-image-icon {
    opacity: 1;
  }
  .slider-card:hover .slider-edit-button {
    opacity: 1;
  }
</style>