<template>
  <div>
    <v-hover 
      v-slot:default="{ hover }">
      <v-card
        @click="toMaterial()"
        :elevation="hover ? 4 : 1"
        class="d-flex ml-3 mr-3 mb-5">
        <v-row class="ml-1 mr-1">
          <v-col cols="12" xs="12" sm="4" md="3" xl="2" class="d-flex align-items-center justify-center">
            <div class="d-flex justify-center" :style="`border-radius: 5px; overflow: hidden; ${$vuetify.breakpoint.xs ? 'width: 100%;' : ''}`">
              <div v-if="item.title_image" :style="`${$vuetify.breakpoint.xs ? 'width: 90%;' : ''}`">
                <img :style="`width: 100%; max-width: ${$vuetify.breakpoint.xs ? '100%;' : '250px;'}`" :src="item.title_image" />
              </div>
            </div>
          </v-col>
          <v-col 
            cols="12" xs="12" sm="8" md="9" xl="10" 
            :style="`display: flex; align-items: center; ${$vuetify.breakpoint.xs ? 'justify-content: center;' : ''}`">
            <div :class="`text-column pa-3 pt-0 pb-0 ${$vuetify.breakpoint.xs ? 'd-flex flex-column align-center' : ''}`">
              <div>
                <span class="mt-0 mb-1 presentation-card-paragraph" style="font-size: 1.1rem; line-height: 1.5rem; padding-bottom: 2px; display: inline-block;">{{ item.title}}</span>
              </div>
              <div class="text" v-html="item.content" style="position: relaitve">
              </div>
              <span style="letter-spacing: 0.065rem; font-size: 0.78rem; color: black; line-height: 2rem;">
                {{`${item.updated_at.substring(0,10)}  ${item.updated_at.substring(11,19)}`}}
              </span>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-hover>
  </div>
</template>

<script>
export default {
  props: ['item'],
  data: () => ({
    routeItem: null,
  }),
  methods: {
    toMaterial() {
      this.$router.push({path: `${this.$router.currentRoute.path}/${this.item.slug}`})
    }
  },
  mounted() {
    console.log(this.item)
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
/* .text >>> *:first-child:after {
  content: 'sdflkj'
} */
</style>

<style lang="scss">
.text {
   overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 5; /* number of lines to show */
   -webkit-box-orient: vertical;
}
.presentation-card-paragraph {
  p {
    font-size: 0.85rem!important;
  }
}
.usefull-material__detail {
  width: 100%;
  // p {
  //   font-size: 16px;
  //   display: block;
  // }
  // .image-column {
  //   width: 100%;
  // }
  // .text-column {
  //   width: 100%;
  //   display: flex;
  //   flex-direction: column;
  // }
}
</style>