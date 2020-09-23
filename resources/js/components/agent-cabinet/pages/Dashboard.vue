<template>
<div ref="dashboard">
<div class="dashboard-wrapper">

  <!-- Mobyle agent info -->
  <div 
    v-if="!hasUserManager && !loading && requestRecieved" 
    class="mobile-agent-not-manager">
    <div class="pa-3 d-flex headline align-center">
      <div class="d-flex">
        <v-icon 
          v-text="'mdi-information'" 
          class="pr-3" 
          size="37" 
          color="red lighten-1"></v-icon>
      </div>
      <div class="agent-has-no-manager">
        За Вами не закрiплений жоден з менеджерів!
      </div>
    </div>
  </div>
  <div 
    v-if="!loading && hasUserManager && requestRecieved" 
    :class="hasAgent ? 'mobile-agent-info active' : 'mobile-agent-info'">
    <span 
      :class="`mobile-manager-title text-center ${xs ? 'xs' : ''}`">
      Ваш менеджер
    </span>
    <div v-if="loading" 
      class="d-flex justify-center align-center">
      <v-progress-circular
        class="ma-3"
        indeterminate
        color="red">
      </v-progress-circular>
    </div>
    <div class="mobile-manager-wrapper">
      <div 
        v-if="hasUserManager && !loading && requestRecieved" 
        class="mobile-manager-content">
        <div 
          v-if="xs"
          class="content-logo">
          <div 
            v-if="!agentData.photo" 
            class="empty-logo-mobile">
            <span class="logo-letter">
              {{ agentData.name }}
            </span>
          </div>
          <div class="agent-photo">
            <img :src="agentData.photo" />
          </div>
        </div>
        <div 
          :class="`manager-data-wrapper ${xs ? 'xs' : ''}`">
          <span class="manager-data-name">
            {{ agentData.name }}
          </span>
          <span style="manager-data-phone">
            <v-icon 
              class="pr-1" 
              v-text="'mdi-phone'" 
              color="black" size="19" ></v-icon>
            {{ agentData.phone }}
          </span>
          <span 
            :class="`email ${xs ? 'xs' : ''}`">
            <v-icon color="black" size="19" class="pr-1" v-text="'mdi-email'"></v-icon>
            {{ agentData.email }}
          </span>
        </div>
      </div>
    </div>
  </div>


  <!-- Dashboard container -->
  <div class="dashboard-container">

    <!-- carousel -->
    <v-card 
      elevation="9" 
      class="carousel-wrapper-card">
      <v-responsive :aspect-ratio="16/9">
        <div 
          class="loader-wrapper"
          v-if="!carouselVisibility && sliderHasNoSlides">
          <v-skeleton-loader
            class="skeleton-loader"
            type="image">
          </v-skeleton-loader>
        </div>
        <div v-if="!carouselVisibility && !slidesLoader" 
          class="absent-slide">
          <span class="absent-slide__text">
            (Пропозиції вiдсутнi)
          </span>
        </div>
        <v-carousel
          transition="fade-transition"
          cycle
          name="dashboard-carousel"
          :class="`${carouselVisibility ? 'dashboard-carousel active' : 'dashboard-carousel'}`"
          id="dashboard-carousel"
          hide-delimiter-background
          :interval="7000"
          :show-arrows="slides && slides.length > 1"
          :hide-delimiters="slides && slides.length === 1 || xs"
          show-arrows-on-hover>
          <v-carousel-item
            v-for="(item, key) in slides"
            :src="item.slide_image"
            :key="key"
            reverse-transition="fade-transition"
            transition="fade-transition">
            <div>
              <div style="height: 50%;">
                <div :class="xs ? 'actions-block-text small-screen' : 'actions-block-text'">
                  <h3 
                    :style="sliderTitleSize">
                    <b>{{ item.title}}</b>
                  </h3>
                  <p :style="`font-size: ${xs ? '0.7rem;' : '0.88rem;'}`"> 
                    {{ item.description }} 
                  </p>
                </div>
              </div>
              <div class="slider-action-btn-wrapper">
                <span 
                  :class="`slider-btn__wrapper ${xs ? 'xs' : ''}`">
                  <v-btn 
                    :x-small="xs || sm && showSidebar || md && showSidebar"
                    :small="sm"
                    :large="$vuetify.breakpoint.lg"
                    class="vuetify_custom-btn white--text" 
                    :to="{name: 'DashboardSlider', path: `slides/${item.slug}`, params: item}">
                    Ознайомитись
                  </v-btn>
                </span>
              </div>
            </div>
          </v-carousel-item>
        </v-carousel>
      </v-responsive>
    </v-card>

    <!-- leasing requests -->
    <v-card 
      v-if="$store.state.leasingRequests.length > 0"
      class="mt-10 mb-6 dashboard-table" elevation="9">
      <v-card-title 
        :class="`headline custom-border pb-3 pt-3 ${!xs ? 'mb-7' : ''}`" >
        Заявки на лiзинг
      </v-card-title>
      <v-data-table
        color="black"
        v-if="$store.state.leasingRequests.length > 0 && $store.state.user.agent"
        :headers="tableHeader"
        :items="$store.state.leasingRequests"
        :class="`dashboard-leasing-request-table ${xs ? 'small' : ''} elevation-1 pb-3`"
        :hide-default-footer="true"
        :items-per-page="5">
        <template v-slot:item.agent_reward="{ item }">
          <span style="white-space: nowrap">
            {{ $formatSum(item.price_brutto) }}
          </span>
        </template>
        <template v-slot:item.leasing_amount="{ item }">
          <span style="white-space: nowrap">
            {{ 
              parseInt(item.leasing_amount.replace(/\s/g, '' ))
                .toLocaleString("en-GB")
                .replace(/,/g, ' ')
            }}
          </span>
        </template>
        <template v-slot:item.data="{item}">
          <span style="white-space: nowrap">
            {{ item.created_at }}
          </span>
        </template>
        <template v-slot:item.leasing_object="{ item }">
          <span style="white-space: nowrap">
            {{ item.leasing_object }}
          </span>
        </template>
        <template v-slot:item.initials="{ item }">
          <span style="white-space: nowrap">
            {{ 
              item.client_type_id == 2 
                ? item.legal_info.company_name 
                : item.last_name + ' ' + item.first_name[0] + '. ' + item.patronymic[0] + '.'  
            }}
          </span>
        </template>
        <template v-slot:item.whole_object="{ item }">
          <v-btn 
            x-small 
            class="btn-hyperlink" 
            small dark 
            color="grey darken-3" 
            :to="{
              name: 'Графiки', 
              params: {data: item.calculation, graph: item.graph_type, preview: true}
            }">
            {{ switchValue(item.graph_type) }}
          </v-btn>
        </template>
        <template #item.status="{ item }">
          <div class="d-flex justify-center">
            <div style="display: inline-block">
              <v-tooltip bottom>
                <template #activator="{ on }">
                  <div v-on="on"  style="display: inline-block">
                    <v-icon
                      v-for="key in progressDivision"
                      :key="key"
                      small
                      :color="applyChanges(item.status_id, key).color">
                      mdi-brightness-1
                    </v-icon>
                  </div>
                </template>
                <span>{{ applyChanges(item.status_id).text }}</span>
              </v-tooltip>
            </div>
          </div>
        </template>
      </v-data-table>
      <div class="d-flex justify-center">
        <span>
          <v-btn
            class="vuetify_custom-btn ma-4"
            style="text-transform: none;"
            to="/leasing-requests"
            color="#e65048"
            dark>
            Показати всі заявки
          </v-btn>
        </span>
      </div>
    </v-card>
  </div>


  <!-- Right-block -->
  <div class="right-block-wrapper">

    <!-- Agent info -->
      <v-card 
        v-show="!lowerThenMedium"
        :class="hasUserManager ? 'agent-info agent-info-active' : 'agent-info'" 
        elevation="8">
        <div 
          class="mt-4 mb-2 pl-4 mb-1 manager-title" >
          {{ managerTitle }}
        </div>
        <div 
          v-if="loading" 
          class="d-flex justify-center align-center">
          <v-progress-circular
            class="ma-3"
            indeterminate
            color="red">
          </v-progress-circular>
        </div>
        <div 
          v-if="hasUserManager && !loading && requestRecieved" 
          class="manager-content d-flex flex-column">
          <div class="d-flex justify-center">
            <div 
              v-if="agentData.photo == null" 
              class="manager-has-no-photo">
              <span class="logo-letter">
                {{ agentData.name }}
              </span>
            </div>
            <div 
              v-if="agentData.photo != null" 
              class="manager-has-photo">
              <img :src="agentData.photo" />
            </div>
          </div>
          <div class="manager-list-wrapper">
            <ul>
              <li class="desktop-manager-name">
                {{ agentData.name }}
              </li>
              <li class="manager-phone">
                <v-icon color="black" size="19" class="pr-1" v-text="'mdi-phone'"></v-icon>
                {{ agentData.phone }}
              </li>
              <li style="color: #bb433c; ">
                <v-icon color="black" size="19" class="pr-1" v-text="'mdi-email'"></v-icon>
                {{ agentData.email }}
              </li>
            </ul>
          </div>
        </div>
      </v-card>

      <!-- News -->
      <v-card 
        v-show="!lowerThenMedium"
        class="dashboard__rigth-block" 
        elevation="8">
        <v-card-title 
          class="news-title title pl-4 pb-2 pt-2">
          Новини
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text 
          v-if="news === null || news === 'undefined' || news.length === 0" 
          class="pt-5 pb-5 text-center">
          <span>Новини вiдсутнi</span>
        </v-card-text>
        <v-card-text 
          v-if="news !== null || news !== 'undefined' || news.length > 0"
          class="pb-0">
          <v-hover 
            v-for="(item, key) in news"
            :key="key"
            v-slot:default="{ hover }">
            <v-card 
              v-if="key < 2"
              :elevation="hover ? 5 : 2" 
              class="news">
              <v-img
                class="white--text align-end"
                style="width: 100%"
                :src="item['news_title_image']">
                <v-card-title 
                  class="subtitle-1" 
                  style="padding-bottom: 4px;">
                  {{ item.title }}
                </v-card-title>
              </v-img>
              <v-card-text>
                <span>{{ item.description }}</span>
                <div style="text-align: right">
                  <span>
                    <v-btn 
                      :to="{name: 'Детально ', path: `/news/${item.slug}`, params: item}" 
                      :small="mdAndDown" 
                      class="vuetify_custom-btn --small capitalize mt-2" 
                      text>
                      Детально
                    </v-btn>
                  </span>
                </div>
              </v-card-text>
            </v-card>
          </v-hover>
        </v-card-text>
        <v-card-text class="pt-0">
          <div style="text-align: right">
            <router-link 
              @click="$setRoute()"
              :to="{ 
                name: 'Новини', 
                path: '/news', 
                params: {news: this.news, redirectFromDashboard: true}
              }" 
              class="see-all-news" tag="span">
              Всi новини
            </router-link>
          </div>
        </v-card-text>
      </v-card>
    </div>
  </div>

  <!-- News for small resolutions -->
  <v-card class="dashboard-news">
    <v-card-title class="dashboard-news__title headline">
      Новини
    </v-card-title>
    <v-card-text 
      v-if="news === null || news === 'undefined' || news.length === 0" 
      class="pt-5 pb-5 text-center">
      <span style="font-size: 1.3rem;">Новини вiдсутнi</span>
    </v-card-text>
    <v-card-text class="dashboard-news__wrapper">
      <v-hover 
        v-for="(item, key) in news"
        :key="key"
        v-slot:default="{ hover }">
        <v-card 
          v-if="key < 2"
          class="news-card"
          :style="xs ? 'width: 92%;' : 'width: 47%;'" 
          :elevation="hover ? 5 : 2">
          <v-img
            class="white--text align-end"
            style="width: 100%; height: 200px;"
            :src="item['news_title_image']">
            <v-card-text 
              class="subtitle-1" 
              style="font-size: 1rem">
              {{ item.title }}
            </v-card-text>
          </v-img>
          <v-card-text class="pb-0">
            {{ item.description }}
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn 
              :to="{name: 'Детально ', path: `/news/${item.slug}`, params: item}" 
              :small="mdAndDown" 
              class="vuetify_custom-btn --small capitalize mt-2" 
              text>
              Детально
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-hover>
    </v-card-text>
    <v-card-text 
      v-if="news !== null && news !== 'undefined' && news.length > 0"
      class="pt-0">
      <div style="text-align: center">
        <v-btn
          @click="$setRoute()" 
          :to="{
            name: 'Новини', 
            path: '/news', 
            params: {news: this.news, redirectFromDashboard: true}
          }" 
          tag="span"
          color="#e75d57"
          outlined>
          всi новини
        </v-btn>
      </div>
    </v-card-text>
  </v-card>
</div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Головна',


  data: () => ({
    tableHeader: [
      { text: 'Клієнт', value: 'initials', align: 'start', sortable: false},
      { text: 'Предмет лiзингу', value: 'leasing_object', align: 'center', sortable: false},
      { text: 'Цiна, грн', value: 'leasing_amount', align: 'center', sortable: false },
      { text: 'АВ, грн', value: 'agent_reward', align: 'center' },
      { text: 'Тип графiку', value: 'whole_object', align: 'center', sortable: false },
      { text: 'Дата подачi', value: 'data', align: 'center', sortable: false },
      { text: 'Статус', value: 'status', align: 'center', sortable: false, width: 120 },
    ],
    carouselVisibility: false,
    sliderHasNoSlides: true,
    slides: null,
    tabledata: [],
    requestRecieved: false,
    news: null,
    progressDivision: 5,

    slidesLoader: false,
  }),


  computed: {
    loading() {
      return this.$store.state.loader === true
    },
    hasUser() {
      return Object.keys(this.$store.state.user).length > 0
    },
    hasAgent() {
      return this.$store.state.agentData.id !== null
    },
    agentData() {
      return this.$store.state.agentData
    },
    hasUserManager() {
      if(!this.$store.state.user.agent) return false
      return this.$store.state.user.agent.manager_id !== null
    },
    mdAndDown() {
      return this.$vuetify.breakpoint.mdAndDown
    },
    xs() {
      return this.$vuetify.breakpoint.xs
    },
    sm() {
      return this.$vuetify.breakpoint.sm
    },
    md() {
      return this.$vuetify.breakpoint.md
    },
    lg() {
      return this.$vuetify.breakpoint.lgAndUp
    },
    showSidebar() {
      return this.$store.state.showSidebar
    },
    lowerThenMedium() {
      return this.$vuetify.breakpoint.width < 1040
    },
    breakpointName() {
      return this.$vuetify.breakpoint.name
    },
    sliderTitleSize() {
      return `font-size: ${
        this.xs 
          ? '0.9rem;' 
          : this.sm 
            ? '1rem;' 
            : this.md && this.showSidebar 
              ? '0.9rem;'
              : this.lg 
                ? '1.4rem;'
                : '1.4rem;'}`
    },
    managerTitle() {
      return !this.requestRecieved 
        ? '' 
        : this.hasUserManager && !this.loading 
          ? 'Ваш менеджер' 
          : 'За Вами не закрiплений жоден з менеджерів!'
    }
  },


  methods: {
    applyChanges(status, index) {
      switch(status) {
        case '0': return {text: 'Відхилена', color: `${index <= 5 ? 'red' : 'grey'}`};
        case '1': return {text: 'В роботі', color: `${index <= 1 ? 'orange' : 'grey'}`};
        case '2': return {text: 'Погоджено', color: `${index <= 2 ? 'orange' : 'grey'}`};
        case '3': return {text: 'Договір підписано', color: `${index <= 3 ? 'green' : 'grey'}`};
        case '4': return {text: 'Отримано аванс', color: `${index <= 4 ? 'green' : 'grey'}` };
        case '5': return {text: 'Відвантажено', color: `${index <= 5 ? 'green darken-2' : 'grey'}`};
        case '6': return {text: 'Відвантажено', color: `${index <= 5 ? 'green darken-2' : 'grey'}`};
        case '7': return {text: 'Відвантажено', color: `${index <= 5 ? 'green darken-2' : 'grey'}`};
      }
    },

    switchValue(val) {
      switch(val) {
        case 'even': return 'Класичний'
        case 'annuity': return 'Ануїтет'
        case 'irregular': return 'Індивідуальний'
      }
    },

    getUserCalculcations() {
      this.$store.commit('toggleSpinner', true)
      const agentId = this.$store.state.user.agent.id

      axios
        .get(`/leasing-reqeust/agent/${agentId}`)
        .then(response => {
          console.log(response.data)
          this.$store.commit('toggleSpinner', false)

          if(response.data.length > 0)  {
            this.$store.commit('addLeasingRequests', response.data)
              
            this.$store.commit('addGraph', response.data)

          } else {
            this.tabledata = []
          }
        })
        .catch(error => {
          this.$catchStatus(error.response.status)

          this.$store.commit('toggleSpinner', false)

          this.$notify({
            group: 'error',
            title: 'Помилка',
            text: `${error.response.status} \n ${error.response.data.message}`,
          })
        })
    },

    sortData(items) {
      items
        .sort((a, b) => {
          return new Date(b.created_at) - new Date(a.created_at)
      })
      return items
    },

    getSlides() {
      this.slidesLoader = true

      axios
        .get('/json/slides')
        .then(response => {
          this.slides = response.data
        })
        .catch(error => {
          this.$catchStatus(error.response.status)

          console.log(error.response)
        })
    },

    getNews() {
      axios
        .get('/json/news')
        .then(response => {
          this.news = response.data
        })
        .catch(error => {
          this.$catchStatus(error.response.status)
          console.log(error)
        })
    }
  },


  watch: {
    slides(val) {
      if(val === null || val.length === 0) {

        setTimeout(() => {
          this.carouselVisibility = false
          this.sliderHasNoSlides = false
          this.slidesLoader = false
        }, 2000)

      } else {
        setTimeout(() => {
          this.carouselVisibility = true
          this.sliderHasNoSlides = false
          this.slidesLoader = false
        }, 2000)
      }
    },

    hasUser() {
      this.getUserCalculcations()
    },

    loading(val) {
      if(val === false){
        setTimeout(() => {
          this.requestRecieved = true
        }, 900)
      }
    }
  },


  created() {
    if(this.hasUser) {
      this.getUserCalculcations()
    }

    this.getSlides()
    this.getNews()
  },
}
</script>

<style lang="scss">
.theme--light.v-skeleton-loader .v-skeleton-loader__avatar, 
.theme--light.v-skeleton-loader .v-skeleton-loader__button, 
.theme--light.v-skeleton-loader .v-skeleton-loader__chip, 
.theme--light.v-skeleton-loader .v-skeleton-loader__divider, 
.theme--light.v-skeleton-loader .v-skeleton-loader__heading, 
.theme--light.v-skeleton-loader .v-skeleton-loader__image, 
.theme--light.v-skeleton-loader .v-skeleton-loader__text {
    background: white!important;
}

.v-skeleton-loader {
  height: 100%!important;
  .v-skeleton-loader__image {
    height: 100%!important;
  }
}

.slider-action-btn-wrapper {

  .slider-btn__wrapper {
    display: inline-block; 
    margin: 2.8rem 3.3rem;

    &.xs {
      margin: 1rem 2rem;
    }
  }

  height: 50%; 
  display: flex; 
  width: 100%; 
  justify-content: flex-end; 
  align-items: flex-end;
}

.theme--light.v-skeleton-loader .v-skeleton-loader__bone:after {
  background: linear-gradient(90deg,transparent,hsla(187, 0%, 81%, 0.82),transparent)!important;
}

.v-carousel__item {
  transition: height 0.2s ease;
  .v-image__image {
    background-size: 100% 100%!important;
  }
}

.manager-list-wrapper {
  display: inline-block; 
  width: 100%; 
  padding: 0 25px;
  margin-top: 15px;

  .manager-phone {
    font-weight: bold; 
    font-family: Montserrat, sans-serif, Arial; 
    font-size: 0.76rem
  }

  ul {
    padding-left: 0!important;
    list-style: none!important;

    li {padding-bottom: 0.28rem}

    li{
      &:hover {
        cursor: pointer;
      }
    }
  }
}
.dashboard-leasing-request-table {
  &.small {

    td {
      min-height: 28px;
    }

    td:last-child {
      margin-bottom: 18px;
    }
  }
}

.logo-letter {
  font-size: 0;
}

.logo-letter:first-letter {
  font-size: 1.4rem;
  font-weight: bold;
}

.manager-content {
  .desktop-manager-name {
    font-size: 1.06rem; 
    font-family: Montserrat,sans-serif,Arial; 
    margin-bottom: 0.2rem;
  }

  .manager-has-no-photo {
    display: flex; 
    align-items: center; 
    justify-content: center; 
    width: 74px; 
    height: 74px; 
    border-radius: 100%; 
    background-color: #dadada;
  }

  .manager-has-photo {
    img {
      max-width: 100%; 
      border-radius: 100%;
    }

    display: flex; 
    text-align: center; 
    justify-content: center; 
    width: 74px; 
    height: 74px; 
    border-radius: 100%; 
    background-color: #dadada;
  }
}

.mobile-manager-content {
  padding: 1.6rem 1rem 0.3rem 1rem; 
  display: flex;
  flex-direction: row;

  .content-logo {
    display: inline-block; 
    width: 70px; 
    position: relaitve;
  }

  span, div:hover {
    cursor: pointer;
  }

  .manager-data-wrapper {
    display: inline-flex; 
    flex-wrap: wrap; 
    justify-content: space-around; 
    width: 90%; 
    padding-top: 10; 
    align-items: center;

    .email {
      color: #bb433c; 
      white-space: nowrap; 
      display: inline-block;

      &.xs {
        text-align: center;
        width: 100%;
        font-size: 10px;
      }
    }

    .manager-data-name {
      font-size: 0.8rem; 
      font-family: Montserrat,sans-serif,Arial; 
      font-weight: bold; 
      white-space: nowrap; 
      padding-right: 0.8rem;
    }

    .manager-data-phone {
      font-family: Montserrat,sans-serif,Arial; 
      font-weight: bold; 
      white-space: nowrap; 
      font-size: 0.76rem; 
      display: inline-block; 
      padding-right: 0.8rem;
    }
  }

  .empty-logo {
    margin-right: 0.5rem; 
    display: inline-flex; 
    justify-content: center; 
    align-items: center; 
    width: 40px; 
    height: 40px; 
    border-radius: 100%; 
    background-color: #dadada;
  }

  .empty-logo-mobile {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 3px;
    left: 4px;
    width: 60px;
    height: 60px;
    border-radius: 100%;
    background-color: #dadada;
  }
}

.manager-title {
  font-family: Montserrat,sans-serif,Arial;
  border-left: 4px solid #e75d57; 
  border-radius: 0!important; 
  font-weight: bold; 
  display: flex; 
  align-items: center; 
  height: 2.2rem; 
  line-height: 1rem;
  position: relative; 
  font-size: 0.95rem;
}

.dashboard-wrapper {
  display: flex; 
  align-items: flex-start; 
  flex-direction: row; 
  justify-content: space-between;
}

.dashboard__custom-btn {
  border-radius: 0!important;
}

.right-block-wrapper {
  width: 24%; 
  display: flex; 
  flex-direction: column;
}

.dashboard-news {

  display: none!important;
  width: 100%!important; 
  margin-top: 35px; 
  box-shadow: 0px 1px 23px 0px #c2c0c0!important;

  .dashboard-news__title {
    border-left: 5px solid #e75d57;
  }

  .dashboard-news__wrapper {

    flex-direction: row; 
    display: flex; 
    flex-wrap: wrap; 
    justify-content: space-around;

    .news-card {
      margin: 10px 0;
    }
  }
}

.mobile-agent-not-manager {
  border-radius: 4px; 
  margin-bottom: 12px;
  width: 100%;
  overflow: hidden;
  display: none;
  border: 2px solid #ef5350;
  transition: max-width 0.5s ease-in;

  .agent-has-no-manager {
    display: flex; 
    align-items: center; 
    font-size: 1rem;
  }
}

.mobile-agent-info {
  position: relative;
  border-radius: 4px; 
  margin-bottom: 12px; 
  overflow: hidden;
  transition: max-width 0.5s ease-in;
  width: 100%;

  .mobile-manager-wrapper {
    width: 100%; 
    min-height: 65px;

    .agent-photo {
      display: inline-flex; 
      justify-content: center; 
      align-items: center; 
      position: absolute; 
      top: 3px; 
      left: 4px; 
      width: 60px; 
      height: 60px;

      img {
        max-width: 100%; 
        border-radius: 100%;
      }
    }
  }

  .mobile-manager-title {
    font-family: Montserrat,sans-serif,Arial;
    top: 0.8rem; 
    font-size: 1rem;
    position: absolute; 
    left: 50%; transform: 
    translate(-50%, -50%); 
    background: #e75d57; 
    padding: 1px 10px; 
    border-radius: 0 0 5px 5px; 
    color: white;

    &.xs {
      font-size: 0.86rem; 
      width: 60%;
    }
  }

  ul {
    padding: 15px;
    list-style: none;
    margin-bottom: 0;
  }

  .agent-list-info {
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.2s ease-in 0.3s;

    &.agent-list-active {
      visibility: visible;
      opacity: 1;
    }
  }

  .icon-wrapper {
    align-items: stretch;
    background: #ef5350;
    padding: 15px;
  }

  display: none;
  box-shadow: 0 3px 1px -2px rgba(0,0,0,.2), 
    0 2px 2px 0 rgba(0,0,0,.14), 
    0 1px 5px 0 rgba(0,0,0,.12);
  
  .agent-desc {
    font-size: 12px;
    padding-right: 4px;
  }

  .agent-data {
    transition: all 0.3s ease;

    &:hover {
      text-decoration: underline;
      cursor: pointer;
    }
  }
}

.agent-info {
  display: flex;
  align-items: center;
  flex-direction: column;
  max-height: 160px;
  width: 100%!important;
  transition: max-height 1s ease!important;

  &.agent-info-active {
    max-height: 650px;
  }
}
.dashboard__rigth-block {
  margin-top: 15px;
  display: block;

  .news-title {
    border-left: 4px solid #e75d57;
  }

  .v-divider {
    margin-bottom: 0!important;
  }

  .news {
    margin-bottom: 20px;
  }
}
.vuetify_custom-btn {

  &.capitalize {
    text-transform: capitalize!important;
  }

  &.--small {
    height: 28px!important;
  }

  border-radius: 0!important;
  background: #e65048!important;
  color: white!important;
}

.dashboard-container {
  flex-direction: column;
  display: flex;
  width: 74%;
  
  .dashboard-table {
    .custom-border {
      border-left: 5px solid #e75d57;
    }

    .btn-hyperlink {
      white-space: nowrap; 
      text-transform: lowercase; 
      display: flex; 
      text-align: center;
    }
  }

  .carousel-wrapper-card {
    transition: height 0.2s ease;

    .loader-wrapper {
      position: relative; 
      width: 100%; 
      height: 100%;

      .skeleton-loader {
        position: absolute; 
        top:0; 
        bottom: 0; 
        right: 0; 
        left: 0;
      }
    }

    .v-responsive {

      .v-responsive__content {

        .dashboard-carousel {
          transition: height 0.2s ease 0s;
          visibility: hidden;
          transition: opacity 1.5s;
          opacity: 0;
          height: 100%!important;

          &.active {
            visibility: visible;
            opacity: 1;
          }
        }

        .v-window__container {
          height: 100%!important;
        }

        .v-carousel__item {
          height: 100%!important;
        }
      }
    }
  }

  .absent-slide {
      text-align: center;
      padding: 15px 10px;

      .absent-slide__text {
        font-size: 1.3rem; 
        color: #727170;
      }
    }

  .v-window {
    position: relative!important;

    .v-window-item  {
      transition: opacity .45s ease-in!important;
      position: absolute!important;
      top: 0!important;
      left: 0!important;
      right: 0!important;
      bottom: 0!important;

      &.fade-transition-leave, .fade-transition-leave-active {
        opacity: 0!important;
      }

      &.fade-transition-enter, .fade-transition-enter-active {
        opacity: 0!important;
      }
    }
  }

  .actions-block-text {
    backdrop-filter: blur(5px); 
    padding: 0 15px; 
    border-radius: 3px;
    background-color: rgba(252, 252, 252, 0.7); 
    max-width: 48%;
    color: black!important;
    margin: 3rem 1.8rem;
    display: inline-block;

    &.small-screen {
      max-width: 90%;
      margin: 25px; 
    }
  }
}

.see-all-news {
  transition: font-size 0.14s ease, color 0.18s ease;
  color: #e65048; 
  font-size: 1rem; 
  text-decoration: underline;
  cursor: pointer;

  &:hover {
    font-size: 1.3rem;
    color: #e3706a;
  }
}
</style>
