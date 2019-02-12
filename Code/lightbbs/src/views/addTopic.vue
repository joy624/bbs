<template>
    <div class="container bg-light">
        <!--标题头-->
        <h5 class="text-center" style="font-weight: bold; color: #636B6F;">
            <i class="fa fa-paint-brush"></i>新建话题
        </h5>
        <form>
            <div class="form-group">
                <!--标题-->
                <input type="text" class="form-control" placeholder="标题">
            </div>
            <!--分类-->
            <div class="form-group">
                <label for="cateFormControlSelect">选择分类</label>
                <select class="form-control" id="cateFormControlSelect">
                    <option>xxx1</option>
                    <option>xxx2</option>
                </select>
            </div>
            <!--编辑区 -->
            <div class="form-group">
                <textarea id="editor"></textarea>
            </div>

            <!--发布按钮-->
            <button type="submit" class="btn btn-primary">发布话题</button>
        </form>
    </div>
</template>
<script>
    import SimpleMDE from 'simplemde'
    import hljs from 'highlight.js'
    window.hljs = hljs

    export default {
        name: 'Create',
        mounted() {
            const simplemde = new SimpleMDE({
                element: $('#editor')[0],
                placeholder: '请使用 Markdown 格式书写 ;-)，代码片段黏贴时请注意使用高亮语法。',
                spellChecker: false,
                autoDownloadFontAwesome: false,
                autosave: {
                    enabled: true,
                    uniqueId: 'content'
                },
                showIcons: ["code", "table"],
                autofocus:true,
                renderingConfig: {
                    codeSyntaxHighlighting: true
                }
            })
        }
    }
</script>
<style scoped>
</style>