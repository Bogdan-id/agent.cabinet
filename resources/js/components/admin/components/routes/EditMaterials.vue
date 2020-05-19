<template>
    <v-card min-height="500">
      <v-card-title>
      </v-card-title>
      <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
    </v-card>
</template>

<script>
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
  import '@ckeditor/ckeditor5-build-classic/build/translations/uk'

  export default {
    name: 'app',
    data: () => ({
      editor: ClassicEditor,
      editorData: '<p>Content of the editor.</p>',
      editorConfig: {
        language: 'uk',
      }
    })
  }
</script>

<style lang="scss">
  .ck {
    &.ck-content {
      min-height: 400px!important;
    }
  }
</style>