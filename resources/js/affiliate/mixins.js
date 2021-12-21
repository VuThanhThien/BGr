import Vue from 'vue';
import moment from "moment";

Vue.mixin({
  computed: {
    format() {
      return this.$store.getters.moneyFormat;
    }
  },
  methods: {
    formatMoney(cents, format) {
      format = format.replace(/(<([^>]+)>)/gi, "");
      cents = cents * 100;
      if (typeof cents == 'string') { cents = cents.replace('.', ''); }
      var value = '';
      var placeholderRegex = /\{\{\s*(\w+)\s*\}\}/;
      var formatString = (format || this.money_format);

      function defaultOption(opt, def) {
        return (typeof opt == 'undefined' ? def : opt);
      }

      function formatWithDelimiters(number, precision, thousands, decimal) {
        precision = defaultOption(precision, 2);
        thousands = defaultOption(thousands, ',');
        decimal = defaultOption(decimal, '.');

        if (isNaN(number) || number == null) { return 0; }

        number = (number / 100.0).toFixed(precision);

        var parts = number.split('.'),
          dollars = parts[0].replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1' + thousands),
          cents = parts[1] ? (decimal + parts[1]) : '';
        return dollars + cents;
      }

      switch (formatString.match(placeholderRegex)[1]) {
        case 'amount':
          value = formatWithDelimiters(cents, 2);
          break;
        case 'amount_no_decimals':
          value = formatWithDelimiters(cents, 0);
          break;
        case 'amount_with_comma_separator':
          value = formatWithDelimiters(cents, 2, '.', ',');
          break;
        case 'amount_no_decimals_with_comma_separator':
          value = formatWithDelimiters(cents, 0, '.', ',');
          break;
      }

      return formatString.replace(placeholderRegex, value);
    },
    genTextCommissionType(commissionType) {
      if (commissionType == 1) {
        return this.$t('percent_sale');
      } else if (commissionType == 2) {
        return this.$t('fix_per_order');
      } else {
        return this.$t('fix_per_item');
      }
    },
    formatCommissionAmount(commissionType, amount, format) {
      if (commissionType != 1) {
        return this.formatMoney(amount, format);
      } else {
        return amount + '%';
      }
    },

    formatTimeRangeDatePicker(str) {
      var date = new Date(str);
      let mnth = ("0" + (date.getMonth() + 1)).slice(-2);
      let day = ("0" + date.getDate()).slice(-2);
      return [date.getFullYear(), mnth, day].join("-");
    },

    makeid(length) {
      var result = '';
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
          charactersLength));
      }
      return result;
    },

    formatDate(timestamp, fromNow=true) {
        let lang = this.$i18n.locale;
        if(lang){
            if(lang == 'zh'){
                moment.locale('zh-cn');
            }
            else{
                moment.locale(lang);
            }
        }
      let date = moment.unix(timestamp);
      if(fromNow) {
        return (
          "<span>" +
          date.fromNow() +
          "<br><small class='text-muted'>" +
          date.format("lll") +
          "</small></span>"
        );
      }else{
        return "<small class='text-muted'>"+date.format("lll") +"</small>";
      }


    },
    selectText(element) {

      if(element.tagName === "INPUT") {
        var input = document.createElement('textarea');
        document.body.appendChild(input);
        input.value = element.value;
        input.select();
        document.execCommand('Copy');
        input.remove();
      }else{
        var range;
        if (document.selection) {
            // IE
            range = document.body.createTextRange();
            range.moveToElementText(element);
            range.select();
        } else if (window.getSelection) {
            range = document.createRange();
            range.selectNode(element);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
        }
        document.execCommand("copy");
      }

    },

    copy(ref, e) {
        if(e){
            let a = e.target.textContent;
            e.target.textContent = this.$t('copied');
            setTimeout(() => {
                e.target.textContent = a;
            }, 1500);
        }
      this.selectText(this.$refs[ref]);

    },

    clipboardSuccessHandler(e) {
      this.$toast.success("Copied", { position: "bottom" })
    },
    generateLinkAffiliate(link){
      if(link)
      {
        let arrLink = link.split("?");
        if (arrLink.length > 1 && arrLink[1] !== "") {
          return link + "&bg_ref=";
        } else {
           return link + "?bg_ref=";
        }
      }
      else{
        return '';
      }
    },
    getTextLocale(key){
        return this.$t(key);
    },
  }
})
