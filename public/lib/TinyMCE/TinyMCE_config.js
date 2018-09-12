tinymce.init({
selector: 'textarea.tinymce',
  setup: function (editor) {
      editor.on('change', function (e) {
          editor.save();
      });
  },
height: 300,
menubar: true,
branding : false,
plugins: [
  "placeholder",
  'advlist autolink lists link image charmap print preview anchor textcolor',
  'searchreplace visualblocks code fullscreen',
  'insertdatetime media table contextmenu paste code help wordcount'
],
toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
content_css: [
  '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
  '//www.tinymce.com/css/codepen.min.css'],
});