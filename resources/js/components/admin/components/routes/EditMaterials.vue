<template>
    <v-card class="admin-edit-materials" min-height="500">
      <v-dialog
        class="copy-file-dialog"
        v-model="copyFileDialog"
        :max-width="320">
        <v-card>
          <v-card-text style="position: relative; font-size: 0.8rem; color: #993b3b;" class="pt-4 pb-0">
            <v-btn 
              @click="copyFileDialog = false" 
              style="position: absolute; right: 4px; top: 6px;" 
              icon small>
              <v-icon small v-text="'mdi-close'"></v-icon>
            </v-btn>
            Посилання на завантажений файл
          </v-card-text>
          <v-card-text class="pb-0">
            <v-text-field
              color="#424242"
              id="link-input"
              style="font-size: 0.8rem!important; font-style: italic;"
              v-model="documentLink"
              dense>
              <template v-slot:append>
                <v-tooltip 
                  top
                  color="black">
                  <template v-slot:activator="{ on }">
                    <v-btn 
                      v-on="on"
                      @click="copyLinkToClipBoard()"
                      small icon>
                      <v-icon 
                        size="18" 
                        color="black" 
                        v-text="'mdi-content-copy'"></v-icon>
                    </v-btn>
                  </template>
                  <span style="font-size: 0.8rem!important;">копiювати посилання</span>
                </v-tooltip>
              </template>
            </v-text-field>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-card-title class="headline">
        Новий матерiал
      </v-card-title>
      <!-- <v-btn @click="sendRequest()">Додати</v-btn> -->
      <v-card-text>
        <v-row>
          <v-col md="4" class="pb-0 pt-0">
            <v-select
              v-if="categoriesPresent"
              @blur="$v.newCategorie.$touch()"
              @change="$v.newCategorie.$touch();"
              :error-messages="newCategorieErr"
              v-model="newCategorie"
              label="Оберiть категорiю"
              :items="categories"
              item-text="name"
              item-value="id"
              outlined dense>
            </v-select>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-text class="pb-0 pt-0">
        <v-textarea
          @input="$v.materialName.$touch()"
          @blur="$v.materialName.$touch()"
          :error-messages="materialNameErr"
          v-model="materialName"
          label="Заголовок матерiалу"
          rows="2"
          outlined>
        </v-textarea>
        <v-row>
          <v-col md="4" class="pb-0">
            <!-- :disabled="!categoriesPresent" -->
            <v-file-input
              v-if="imageName === null"
              @change="previewImage($event)"
              id="material-image"
              v-model="materialImg"
              accept="image/x-png, image/gif, image/jpeg, image/jpg, image/svg+xml"
              :rules="rules"
              prepend-inner-icon="mdi-camera"
              label="Зображення до матерiалу"
              outlined show-size dense>
            </v-file-input>
          </v-col>
        </v-row>
        <div v-if="materialImgPreview !== null || imageName !== null" style="width: 365px; position: relative;">
          <v-btn @click="clearImage()" icon style="position: absolute; right: -5px; top: -35px;">
            <v-icon large color="black" v-text="'mdi-close'"></v-icon>
          </v-btn>
        </div>
        <img
          v-if="materialImgPreview !== null || imageName !== null"
          :src="materialImgPreview || imageName"
          width="365"
          style="border: 2px solid black; border-radius: 5px;"
          class="grey darken-4" />
      </v-card-text>
      <v-card-text>
        <v-card-title 
          class="headline black--text"
          style="text-decoration: underline;">
          Контент
        </v-card-title>

        <!-- hidden input for files -->
        <input 
          ref="ckDwnlInput"
          id="ck-dwnl-input"
          @change="handleFile()"
          type="file" 
          style="visibility: hidden; position: absolute; top: 0; right: 0;">
          
        <!-- btn for ck-edito panel -->
        <v-icon
          @click="toggleFileDialog($event)"
          id="ck-dwnl-btn"
          style="cursor: pointer;"
          color="grey darken-3"
          v-text="'mdi-download'"
          :size="20" 
          icon >
        </v-icon>

        <ckeditor
          :editor="editor" v-model="editorData" @ready="onEditorReady" :config="editorConfig">
        </ckeditor>
        <v-card-text class="d-flex justify-center">
          <span>
            <v-btn 
            @click="submit()" 
            :loading="loading"
            dark 
            class="error">
              {{ $route.params.edit ? 'Зберегти' : 'Додати матерiал'}}
            </v-btn>
          </span>
        </v-card-text>
      </v-card-text>
    </v-card>
</template>

<script>
  // import '@ckeditor/ckeditor5-build-classic/build/translations/uk'
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
  // import LinkImage from '@ckeditor/ckeditor5-link/src/linkimage'

  import List from '@ckeditor/ckeditor5-list/src/list'

  import axios from 'axios'
  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'

  export default {
    mixins: [validationMixin],
    data: () => ({
      copyFileDialog: false,
      documentLink: null,
      fileName: null,

      tempHref: null,

      commonErr: ['Обов`язкове поле'],
      editor: ClassicEditor,
      ckEditor: null,
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

        // link: {
        //     addTargetToExternalLinks: true,

        //     decorators: [
        //       {
        //         mode: 'manual',
        //         label: 'Downloadable',
        //         attributes: {
        //             download: 'download'
        //         }
        //       }
        //     ]
        // },
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          }
        }
      },

      rules: [
        value => !value || value.size < 2000000 || 'Розмiр зображення повинен бути меньше 2 MB!',
      ],

      categories: [],
      loading: false,

      editorData: '<p>Контент</p>',
      materialName: null,

      materialImg: null,
      materialImgPreview: null,
      imageName: null,
      imageToDelete: null,

      newCategorie: null
    }),
    validations: {
      newCategorie: { required },
      materialName: { required },
      // materialImg: {required}
    },
    computed: {
      categoriesPresent() {
        if(!this.categories) return false
        return this.categories.length > 0
      },

      newCategorieErr() {
        if (!this.$v.newCategorie.$error) return
        return this.commonErr
      },

      materialNameErr() {
        if (!this.$v.materialName.$error) return
        return this.commonErr
      },

      materialImgErr() {
        if (!this.$v.materialImg.$error) return
        return this.commonErr
      }
    },
    methods: {
      onEditorReady(editor) {

        let inst = {editor}
        console.log(inst.editor)
        this.ckEditor = inst.editor
      },

      copyLinkToClipBoard() {
        let input = document.getElementById('link-input')

        input.select()
        input.setSelectionRange(0, 99999)

        this.tempHref = document.execCommand("copy")

        this.$notify({
          group: 'success',
          title: ``,
          text: 'Посилання скопійоване в буфер обміну'
        })
      },
      toggleFileDialog(event) {
        if (event) {
          event.preventDefault()
        }

        this.$refs.ckDwnlInput.click();
      },
      handleFile() {
        let el = document.getElementById('ck-dwnl-input')
        let file = el.files[0]

        this.fileName = file.name

        this.uploadFile(file)
      },

      uploadFile(file) {
        let formData = new FormData()
        formData.append('upload', file)
        
        axios
          .post('/admin/document/upload', formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              }
          })
          .then(response => {
            console.log(response)

            this.copyFileDialog = !this.copyFileDialog

            this.$nextTick(() => {
              this.documentLink = response.data.url
              document.getElementById('link-input').readOnly = true
            })
            
          })
          .catch(error => {
            this.$catchStatus(error.response.status, error)
          })
      },
            
      clearImage() {
        this.imageName = null 
        this.materialImg = null
        this.materialImgPreview = null
        if(this.$route.params.edit === true) {
          axios
            .post('/admin/image/delete', {image: this.imageToDelete})
            .then(() => {
            })
            .catch(error => {
              this.$catchStatus(error.response.status, error)
            })
        }
      },

      submit() {
        !this.$v.$invalid
          ? this.sendRequest()
          : this.highlightErrors()
      },

      sendRequest() {
        this.loading = true
        let url
        if(this.$route.params.edit === true) {
          url = `/admin/useful-material/update/${this.$route.params.id}`
        } else {
          url = '/admin/useful-material/create'
        }
        axios
          .post(url, this.finalObj())
          .then(() => {
            this.loading = false
            this.$notify({
              group: 'success',
              title: 'Успiшно',
              text: ``,
            })
            setTimeout(() => {
              this.$router.push({name: 'Роздiл Кориснi матерiали', params: {reload: true}})
            }, 200);
          })
          .catch(error => {
            this.$catchStatus(error.response.status, error)
            this.loading = false
          })
      },

      finalObj() {
        return {
          usefulMaterialsCategoryId: this.newCategorie,
          titleImage: this.imageName,
          title: this.materialName, 
          content: this.editorData,
          _token: this.getCsrf()
        }
      },

      previewImage(event) { 
        if(event) {
          if(event.size > 2000000) {
            this.materialImg = null
            this.$notify({
              group: 'error',
              title: 'Помилка',
              text: `Розмiр зображення не повинен перевищувати 2 mb`,
            })
            return
          } else {
            this.imageName = event.name
            const reader = new FileReader()
            reader.readAsDataURL(event)
            reader.onload = (data) => {
              this.materialImgPreview = data.currentTarget.result
            }
            this.uploadTitleImage(event)
          }
        } else {
          this.materialImgPreview = null
        }
      },

      uploadTitleImage(event) {
        let formData = new FormData()
        formData.append('upload', event)
        axios
          .post('/admin/image/upload', formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              }
          })
          .then(response => {
            this.imageName = response.data.url
          })
          .catch(error => {
            this.$catchStatus(error.response.status, error)
          })
      },

      getCsrf() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },

      highlightErrors() {
        this.$v.$anyError
        this.$v.$touch()
      },

      assignTokenToCkEditorConfig() {
        this.editorConfig.simpleUpload.headers.Authorization = `Bearer ${this.getCsrf()}`
      },

      embedDwnldBtnToCkPnl() {
        setTimeout(() => {
          let btn = document.getElementById('ck-dwnl-btn')
          let panel = document.querySelector('.ck-toolbar__items')

          let event = new Event('click', {bubbles: true})
          panel.dispatchEvent(event)

          panel.appendChild(btn)
        }, 100)
      },

      getLinkBtn(element) {
        let temp = null
        for(let i = 0; i < element.length; i++ ) {
          console.log(element[i].querySelector('span span'))
          if(element[i].querySelector('span span').textContent.split(' ')[0] === 'Посилання') {
            temp = element[i]
          }
        }
        return temp
      },

      setLstLinkBtnClick() {
        this.$nextTick(() => {

          let el = document.querySelector('.ck-button-save')
          el.addEventListener('click', this.formatLink)

          let input = document.querySelector('.ck-input-text')
          let pasteEvent = new Event('input', {bubbles: true})

          input.addEventListener('input', this.getValueFromInput)
          input.dispatchEvent(pasteEvent)

        })
      },

      getValueFromInput(e) {
        if(e) {
          this.documentLink = e.target.value
        }
      },

      writeLinkToEditor() {
        this.ckEditor.model.change( writer => {

          let linkedText = writer
            .createText( this.fileName, { linkHref: this.documentLink} )

            console.log(linkedText)

          this.ckEditor.model
            .insertContent( linkedText, this.ckEditor.model.document.selection )

        })
      },

      formatLink() {
        setTimeout(() => {
          this.writeLinkToEditor()
        }, 0)
      },
    },

    mounted() {
      setTimeout(() => {
        let ckBtnLink = document.querySelectorAll('.ck-button')
        let getLinkBtn = this.getLinkBtn(ckBtnLink)

        console.log(getLinkBtn)

        getLinkBtn.addEventListener('click', this.setLstLinkBtnClick)
      }, 1000)

      this.assignTokenToCkEditorConfig()

      this.embedDwnldBtnToCkPnl()

      this.categories = this.$route.params.categories
    },

    created() {
      let material = this.$route.params.material

      if(this.$route.params.edit === true) {

        this.editorData = material.content,
        this.materialName = material.title,
        this.imageName = material.title_image

        if(material.title_image) {

          let index = material.title_image.lastIndexOf("/") + 1
          this.imageToDelete = material.title_image.substr(index)
        }

        this.materialImg = material.title_image
        this.newCategorie = this.$route.params.category[0].id
      }
    }
  }
</script>

<style lang="scss">
  .ck {
    &.ck-content {
      min-height: 200px!important;
    }
  }

  .admin-edit-materials {
    .v-dialog__content .v-dialog, .v-dialog__content--active .v-dialog {
        overflow-x: scroll !important;
    }
  }
</style>