<template>
  <v-card-text class="d-flex justify-space-around align-center">
    <span>
      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
        <v-btn
          v-on="on"
          color="black" 
          dark small icon>
          <v-icon dark v-text="'mdi-email-send'"></v-icon>
        </v-btn>
        </template>
        <span>Надiслати розрахунок на email</span>
      </v-tooltip>
    </span>
    <span>
      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn
            v-on="on"
            color="black" 
            icon small dark>
            <v-icon dark>mdi-plus-thick</v-icon>
          </v-btn>
        </template>
        <span>Створити заявку на лiзинг</span>
      </v-tooltip>
    </span>
    <span>
      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn 
            v-on="on"
            color="black" 
            small icon dark>
            <v-icon dark v-text="'mdi-download'"></v-icon>
          </v-btn>
        </template>
        <span>Завантажити розрахунок у форматi Pdf</span>
      </v-tooltip>
    </span>
  </v-card-text>
</template>