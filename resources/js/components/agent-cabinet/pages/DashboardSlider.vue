<template>
<v-row>
  <v-col cols="12" class="pt-0">
    <v-card class="usefull-material__detail " elevation="7" v-if="offer">
      <v-card-text style="font-size: 1.25rem;">
        <div style="color: black;">{{ offer.title }}</div>
      </v-card-text>
      <v-divider class="ml-4 mr-4 mb-1 mt-0"></v-divider>
      <div style="padding: 0 25px; text-align: left;">
        <span style="letter-spacing: 0.05rem; font-size: 0.79rem; display: inline-block;">
          {{`${$formatDate(offer.updated_at)}`}}&nbsp; {{`${offer.updated_at.substring(11,19)}`}}
        </span>
      </div>
      <div style="display: flex; justify-content: center; padding: 15px 22px 25px 22px;">
        <img :src="offer.slide_image" style="width: 100%; max-width: 990px;"/>
      </div>
      <v-card-text v-html="offer.content" class="current-material" style="overflow: hidden;">
      </v-card-text>
    </v-card>
  </v-col>
</v-row>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    offer: null,
    slides: []
  }),
  methods: {
    getAllSlides() {
      this.slides.splice(0)
      axios
        .get('/json/slides')
        .then(response => {
          this.slides.push(...response.data)
          this.getCurrentSlide()
        })
        .catch(error => this.$catchStatus(error))
    },
    getCurrentSlide() {
      if(this.slides.length) {
        this.offer = this.slides.filter(v => {
          return v.slug === this.$route.params.slug
        })[0]
      }
    }
  },
  created() {
    if(Object.keys(this.$route.params).length > 1) {
      this.offer = this.$route.params
    } else {
      this.getAllSlides()
    }
      
  },
  mounted() {
    document.body.scrollTop = document.documentElement.scrollTop = 0
  }
}
</script>