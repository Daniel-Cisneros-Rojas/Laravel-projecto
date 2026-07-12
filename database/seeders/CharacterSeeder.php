<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Animales
        $animales = Theme::where('slug', 'animales')->first();
        if ($animales) {
            $this->createAnimals($animales);
        }

        // Profesiones
        $profesiones = Theme::where('slug', 'profesiones')->first();
        if ($profesiones) {
            $this->createProfessions($profesiones);
        }

        // Comida
        $comida = Theme::where('slug', 'comida')->first();
        if ($comida) {
            $this->createFood($comida);
        }

        // Naturaleza
        $naturaleza = Theme::where('slug', 'naturaleza')->first();
        if ($naturaleza) {
            $this->createNature($naturaleza);
        }

        // Familia
        $familia = Theme::where('slug', 'familia')->first();
        if ($familia) {
            $this->createFamily($familia);
        }

        // Escuela
        $escuela = Theme::where('slug', 'escuela')->first();
        if ($escuela) {
            $this->createSchool($escuela);
        }

        // Números
        $numeros = Theme::where('slug', 'numeros')->first();
        if ($numeros) {
            $this->createNumbers($numeros);
        }

        // Colores
        $colores = Theme::where('slug', 'colores')->first();
        if ($colores) {
            $this->createColors($colores);
        }
    }

    private function createAnimals(Theme $theme): void
    {
        $characters = [
            ['hanzi' => '马', 'pinyin' => 'mǎ', 'meaning' => 'Caballo', 'level' => 1, 'example_sentence' => '白马 - Caballo blanco'],
            ['hanzi' => '狮子', 'pinyin' => 'shīzi', 'meaning' => 'León', 'level' => 2, 'example_sentence' => '非洲狮子 - León africano'],
            ['hanzi' => '熊', 'pinyin' => 'xióng', 'meaning' => 'Oso', 'level' => 1, 'example_sentence' => '大熊 - Oso grande'],
            ['hanzi' => '猫', 'pinyin' => 'māo', 'meaning' => 'Gato', 'level' => 1, 'example_sentence' => '黑猫 - Gato negro'],
            ['hanzi' => '犬', 'pinyin' => 'quǎn', 'meaning' => 'Perro', 'level' => 2, 'example_sentence' => '小犬 - Perrito'],
            ['hanzi' => '鸟', 'pinyin' => 'niǎo', 'meaning' => 'Pájaro', 'level' => 1, 'example_sentence' => '红鸟 - Pájaro rojo'],
            ['hanzi' => '鱼', 'pinyin' => 'yú', 'meaning' => 'Pez', 'level' => 1, 'example_sentence' => '金鱼 - Pez dorado'],
            ['hanzi' => '龙', 'pinyin' => 'lóng', 'meaning' => 'Dragón', 'level' => 2, 'example_sentence' => '中国龙 - Dragón chino'],
            ['hanzi' => '蛇', 'pinyin' => 'shé', 'meaning' => 'Serpiente', 'level' => 2, 'example_sentence' => '绿蛇 - Serpiente verde'],
            ['hanzi' => '羊', 'pinyin' => 'yáng', 'meaning' => 'Oveja/Cabra', 'level' => 1, 'example_sentence' => '绵羊 - Oveja'],
            ['hanzi' => '猪', 'pinyin' => 'zhū', 'meaning' => 'Cerdo', 'level' => 1, 'example_sentence' => '小猪 - Cerdito'],
            ['hanzi' => '象', 'pinyin' => 'xiàng', 'meaning' => 'Elefante', 'level' => 2, 'example_sentence' => '大象 - Elefante'],
            ['hanzi' => '兔', 'pinyin' => 'tù', 'meaning' => 'Conejo', 'level' => 1, 'example_sentence' => '白兔 - Conejo blanco'],
            ['hanzi' => '蛙', 'pinyin' => 'wā', 'meaning' => 'Rana', 'level' => 2, 'example_sentence' => '绿蛙 - Rana verde'],
            ['hanzi' => '蜂', 'pinyin' => 'fēng', 'meaning' => 'Abeja', 'level' => 2, 'example_sentence' => '蜜蜂 - Abeja'],
        ];

        foreach ($characters as $char) {
            Character::create(array_merge($char, ['theme_id' => $theme->id]));
        }
    }

    private function createProfessions(Theme $theme): void
    {
        $characters = [
            ['hanzi' => '医生', 'pinyin' => 'yīshēng', 'meaning' => 'Médico', 'level' => 1, 'example_sentence' => '医生很忙 - El médico está muy ocupado'],
            ['hanzi' => '老师', 'pinyin' => 'lǎoshī', 'meaning' => 'Profesor', 'level' => 1, 'example_sentence' => '英语老师 - Profesor de inglés'],
            ['hanzi' => '工程师', 'pinyin' => 'gōngchéngshī', 'meaning' => 'Ingeniero', 'level' => 3, 'example_sentence' => '软件工程师 - Ingeniero de software'],
            ['hanzi' => '厨师', 'pinyin' => 'chúshī', 'meaning' => 'Cocinero', 'level' => 2, 'example_sentence' => '中国厨师 - Cocinero chino'],
            ['hanzi' => '警察', 'pinyin' => 'jǐngchá', 'meaning' => 'Policía', 'level' => 2, 'example_sentence' => '警察叔叔 - Tío policía'],
            ['hanzi' => '演员', 'pinyin' => 'yǎnyuán', 'meaning' => 'Actor', 'level' => 2, 'example_sentence' => '电影演员 - Actor de cine'],
            ['hanzi' => '商人', 'pinyin' => 'shāngrén', 'meaning' => 'Comerciante', 'level' => 2, 'example_sentence' => '成功的商人 - Comerciante exitoso'],
            ['hanzi' => '农民', 'pinyin' => 'nóngmín', 'meaning' => 'Granjero', 'level' => 2, 'example_sentence' => '乡村农民 - Granjero rural'],
            ['hanzi' => '科学家', 'pinyin' => 'kēxuéjiā', 'meaning' => 'Científico', 'level' => 3, 'example_sentence' => '著名科学家 - Científico famoso'],
            ['hanzi' => '艺术家', 'pinyin' => 'yìshùjiā', 'meaning' => 'Artista', 'level' => 2, 'example_sentence' => '年轻艺术家 - Artista joven'],
        ];

        foreach ($characters as $char) {
            Character::create(array_merge($char, ['theme_id' => $theme->id]));
        }
    }

    private function createFood(Theme $theme): void
    {
        $characters = [
            ['hanzi' => '米', 'pinyin' => 'mǐ', 'meaning' => 'Arroz', 'level' => 1, 'example_sentence' => '白米 - Arroz blanco'],
            ['hanzi' => '面', 'pinyin' => 'miàn', 'meaning' => 'Fideos/Harina', 'level' => 1, 'example_sentence' => '面条 - Fideos'],
            ['hanzi' => '肉', 'pinyin' => 'ròu', 'meaning' => 'Carne', 'level' => 1, 'example_sentence' => '牛肉 - Carne de res'],
            ['hanzi' => '鸡', 'pinyin' => 'jī', 'meaning' => 'Pollo', 'level' => 1, 'example_sentence' => '炒鸡 - Pollo frito'],
            ['hanzi' => '鱼', 'pinyin' => 'yú', 'meaning' => 'Pescado', 'level' => 1, 'example_sentence' => '鲜鱼 - Pescado fresco'],
            ['hanzi' => '菜', 'pinyin' => 'cài', 'meaning' => 'Verdura/Plato', 'level' => 1, 'example_sentence' => '绿菜 - Verdura verde'],
            ['hanzi' => '果', 'pinyin' => 'guǒ', 'meaning' => 'Fruta', 'level' => 1, 'example_sentence' => '水果 - Fruta'],
            ['hanzi' => '苹果', 'pinyin' => 'píngguǒ', 'meaning' => 'Manzana', 'level' => 1, 'example_sentence' => '红苹果 - Manzana roja'],
            ['hanzi' => '香蕉', 'pinyin' => 'xiāngjiāo', 'meaning' => 'Plátano', 'level' => 2, 'example_sentence' => '黄香蕉 - Plátano amarillo'],
            ['hanzi' => '面包', 'pinyin' => 'miànbāo', 'meaning' => 'Pan', 'level' => 2, 'example_sentence' => '白面包 - Pan blanco'],
            ['hanzi' => '蛋', 'pinyin' => 'dàn', 'meaning' => 'Huevo', 'level' => 1, 'example_sentence' => '鸡蛋 - Huevo'],
            ['hanzi' => '奶', 'pinyin' => 'nǎi', 'meaning' => 'Leche', 'level' => 1, 'example_sentence' => '牛奶 - Leche'],
            ['hanzi' => '酒', 'pinyin' => 'jiǔ', 'meaning' => 'Bebida alcohólica', 'level' => 2, 'example_sentence' => '中国酒 - Bebida alcohólica china'],
            ['hanzi' => '茶', 'pinyin' => 'chá', 'meaning' => 'Té', 'level' => 1, 'example_sentence' => '绿茶 - Té verde'],
            ['hanzi' => '水', 'pinyin' => 'shuǐ', 'meaning' => 'Agua', 'level' => 1, 'example_sentence' => '冷水 - Agua fría'],
        ];

        foreach ($characters as $char) {
            Character::create(array_merge($char, ['theme_id' => $theme->id]));
        }
    }

    private function createNature(Theme $theme): void
    {
        $characters = [
            ['hanzi' => '山', 'pinyin' => 'shān', 'meaning' => 'Montaña', 'level' => 1, 'example_sentence' => '高山 - Montaña alta'],
            ['hanzi' => '水', 'pinyin' => 'shuǐ', 'meaning' => 'Agua', 'level' => 1, 'example_sentence' => '清水 - Agua clara'],
            ['hanzi' => '火', 'pinyin' => 'huǒ', 'meaning' => 'Fuego', 'level' => 1, 'example_sentence' => '大火 - Fuego grande'],
            ['hanzi' => '木', 'pinyin' => 'mù', 'meaning' => 'Árbol/Madera', 'level' => 1, 'example_sentence' => '大木 - Árbol grande'],
            ['hanzi' => '土', 'pinyin' => 'tǔ', 'meaning' => 'Tierra', 'level' => 1, 'example_sentence' => '黄土 - Tierra amarilla'],
            ['hanzi' => '风', 'pinyin' => 'fēng', 'meaning' => 'Viento', 'level' => 1, 'example_sentence' => '强风 - Viento fuerte'],
            ['hanzi' => '云', 'pinyin' => 'yún', 'meaning' => 'Nube', 'level' => 1, 'example_sentence' => '白云 - Nube blanca'],
            ['hanzi' => '雨', 'pinyin' => 'yǔ', 'meaning' => 'Lluvia', 'level' => 1, 'example_sentence' => '大雨 - Lluvia fuerte'],
            ['hanzi' => '雪', 'pinyin' => 'xuě', 'meaning' => 'Nieve', 'level' => 1, 'example_sentence' => '白雪 - Nieve blanca'],
            ['hanzi' => '花', 'pinyin' => 'huā', 'meaning' => 'Flor', 'level' => 1, 'example_sentence' => '红花 - Flor roja'],
            ['hanzi' => '草', 'pinyin' => 'cǎo', 'meaning' => 'Pasto/Hierba', 'level' => 1, 'example_sentence' => '绿草 - Pasto verde'],
            ['hanzi' => '太阳', 'pinyin' => 'tàiyáng', 'meaning' => 'Sol', 'level' => 2, 'example_sentence' => '明亮的太阳 - Sol brillante'],
            ['hanzi' => '月亮', 'pinyin' => 'yuèliàng', 'meaning' => 'Luna', 'level' => 2, 'example_sentence' => '圆月亮 - Luna redonda'],
            ['hanzi' => '星', 'pinyin' => 'xīng', 'meaning' => 'Estrella', 'level' => 1, 'example_sentence' => '闪星 - Estrella brillante'],
            ['hanzi' => '海', 'pinyin' => 'hǎi', 'meaning' => 'Mar/Océano', 'level' => 1, 'example_sentence' => '蓝海 - Mar azul'],
        ];

        foreach ($characters as $char) {
            Character::create(array_merge($char, ['theme_id' => $theme->id]));
        }
    }

    private function createFamily(Theme $theme): void
    {
        $characters = [
            ['hanzi' => '爸爸', 'pinyin' => 'bàba', 'meaning' => 'Padre', 'level' => 1, 'example_sentence' => '我的爸爸 - Mi padre'],
            ['hanzi' => '妈妈', 'pinyin' => 'māma', 'meaning' => 'Madre', 'level' => 1, 'example_sentence' => '我的妈妈 - Mi madre'],
            ['hanzi' => '哥哥', 'pinyin' => 'gēge', 'meaning' => 'Hermano mayor', 'level' => 1, 'example_sentence' => '我的哥哥 - Mi hermano mayor'],
            ['hanzi' => '妹妹', 'pinyin' => 'mèimei', 'meaning' => 'Hermana menor', 'level' => 1, 'example_sentence' => '我的妹妹 - Mi hermana menor'],
            ['hanzi' => '姐姐', 'pinyin' => 'jiějie', 'meaning' => 'Hermana mayor', 'level' => 1, 'example_sentence' => '我的姐姐 - Mi hermana mayor'],
            ['hanzi' => '弟弟', 'pinyin' => 'dìdi', 'meaning' => 'Hermano menor', 'level' => 1, 'example_sentence' => '我的弟弟 - Mi hermano menor'],
            ['hanzi' => '爷爷', 'pinyin' => 'yéye', 'meaning' => 'Abuelo', 'level' => 2, 'example_sentence' => '我的爷爷 - Mi abuelo'],
            ['hanzi' => '奶奶', 'pinyin' => 'nǎinai', 'meaning' => 'Abuela', 'level' => 2, 'example_sentence' => '我的奶奶 - Mi abuela'],
            ['hanzi' => '伯伯', 'pinyin' => 'bábo', 'meaning' => 'Tío', 'level' => 2, 'example_sentence' => '我的伯伯 - Mi tío'],
            ['hanzi' => '阿姨', 'pinyin' => 'āyí', 'meaning' => 'Tía', 'level' => 2, 'example_sentence' => '我的阿姨 - Mi tía'],
            ['hanzi' => '表哥', 'pinyin' => 'biǎogē', 'meaning' => 'Primo', 'level' => 3, 'example_sentence' => '我的表哥 - Mi primo'],
            ['hanzi' => '表妹', 'pinyin' => 'biǎomèi', 'meaning' => 'Prima', 'level' => 3, 'example_sentence' => '我的表妹 - Mi prima'],
            ['hanzi' => '孩子', 'pinyin' => 'háizi', 'meaning' => 'Niño', 'level' => 1, 'example_sentence' => '小孩子 - Niño pequeño'],
            ['hanzi' => '家', 'pinyin' => 'jiā', 'meaning' => 'Hogar/Casa', 'level' => 1, 'example_sentence' => '我的家 - Mi hogar'],
            ['hanzi' => '人', 'pinyin' => 'rén', 'meaning' => 'Persona', 'level' => 1, 'example_sentence' => '好人 - Buena persona'],
        ];

        foreach ($characters as $char) {
            Character::create(array_merge($char, ['theme_id' => $theme->id]));
        }
    }

    private function createSchool(Theme $theme): void
    {
        $characters = [
            ['hanzi' => '学校', 'pinyin' => 'xuéxiào', 'meaning' => 'Escuela', 'level' => 1, 'example_sentence' => '我的学校 - Mi escuela'],
            ['hanzi' => '书', 'pinyin' => 'shū', 'meaning' => 'Libro', 'level' => 1, 'example_sentence' => '中文书 - Libro de chino'],
            ['hanzi' => '笔', 'pinyin' => 'bǐ', 'meaning' => 'Bolígrafo', 'level' => 1, 'example_sentence' => '红笔 - Bolígrafo rojo'],
            ['hanzi' => '纸', 'pinyin' => 'zhǐ', 'meaning' => 'Papel', 'level' => 1, 'example_sentence' => '白纸 - Papel blanco'],
            ['hanzi' => '黑板', 'pinyin' => 'hēibǎn', 'meaning' => 'Pizarra', 'level' => 2, 'example_sentence' => '教室黑板 - Pizarra del aula'],
            ['hanzi' => '课', 'pinyin' => 'kè', 'meaning' => 'Clase/Lección', 'level' => 1, 'example_sentence' => '数学课 - Clase de matemáticas'],
            ['hanzi' => '学生', 'pinyin' => 'xuésheng', 'meaning' => 'Estudiante', 'level' => 1, 'example_sentence' => '好学生 - Buen estudiante'],
            ['hanzi' => '考试', 'pinyin' => 'kǎoshì', 'meaning' => 'Examen', 'level' => 2, 'example_sentence' => '数学考试 - Examen de matemáticas'],
            ['hanzi' => '字典', 'pinyin' => 'zìdiǎn', 'meaning' => 'Diccionario', 'level' => 2, 'example_sentence' => '中英字典 - Diccionario chino-inglés'],
            ['hanzi' => '教室', 'pinyin' => 'jiàoshì', 'meaning' => 'Aula', 'level' => 2, 'example_sentence' => '大教室 - Aula grande'],
            ['hanzi' => '数学', 'pinyin' => 'shùxué', 'meaning' => 'Matemáticas', 'level' => 2, 'example_sentence' => '学数学 - Aprender matemáticas'],
            ['hanzi' => '英语', 'pinyin' => 'yīngyǔ', 'meaning' => 'Inglés', 'level' => 2, 'example_sentence' => '英语课 - Clase de inglés'],
            ['hanzi' => '中文', 'pinyin' => 'zhōngwén', 'meaning' => 'Chino', 'level' => 2, 'example_sentence' => '学中文 - Aprender chino'],
            ['hanzi' => '科学', 'pinyin' => 'kēxué', 'meaning' => 'Ciencia', 'level' => 2, 'example_sentence' => '科学课 - Clase de ciencia'],
            ['hanzi' => '美术', 'pinyin' => 'měishù', 'meaning' => 'Artes/Dibujo', 'level' => 2, 'example_sentence' => '美术课 - Clase de artes'],
        ];

        foreach ($characters as $char) {
            Character::create(array_merge($char, ['theme_id' => $theme->id]));
        }
    }

    private function createNumbers(Theme $theme): void
    {
        $characters = [
            ['hanzi' => '一', 'pinyin' => 'yī', 'meaning' => 'Uno', 'level' => 1, 'example_sentence' => '一个苹果 - Una manzana'],
            ['hanzi' => '二', 'pinyin' => 'èr', 'meaning' => 'Dos', 'level' => 1, 'example_sentence' => '二个橙子 - Dos naranjas'],
            ['hanzi' => '三', 'pinyin' => 'sān', 'meaning' => 'Tres', 'level' => 1, 'example_sentence' => '三只鸟 - Tres pájaros'],
            ['hanzi' => '四', 'pinyin' => 'sì', 'meaning' => 'Cuatro', 'level' => 1, 'example_sentence' => '四个季节 - Cuatro estaciones'],
            ['hanzi' => '五', 'pinyin' => 'wǔ', 'meaning' => 'Cinco', 'level' => 1, 'example_sentence' => '五个手指 - Cinco dedos'],
            ['hanzi' => '六', 'pinyin' => 'liù', 'meaning' => 'Seis', 'level' => 1, 'example_sentence' => '六个鸡蛋 - Seis huevos'],
            ['hanzi' => '七', 'pinyin' => 'qī', 'meaning' => 'Siete', 'level' => 1, 'example_sentence' => '七天 - Siete días'],
            ['hanzi' => '八', 'pinyin' => 'bā', 'meaning' => 'Ocho', 'level' => 1, 'example_sentence' => '八个馒头 - Ocho bollos'],
            ['hanzi' => '九', 'pinyin' => 'jiǔ', 'meaning' => 'Nueve', 'level' => 1, 'example_sentence' => '九个月 - Nueve meses'],
            ['hanzi' => '十', 'pinyin' => 'shí', 'meaning' => 'Diez', 'level' => 1, 'example_sentence' => '十个学生 - Diez estudiantes'],
            ['hanzi' => '百', 'pinyin' => 'bǎi', 'meaning' => 'Cien', 'level' => 2, 'example_sentence' => '一百块钱 - Cien yuanes'],
            ['hanzi' => '千', 'pinyin' => 'qiān', 'meaning' => 'Mil', 'level' => 2, 'example_sentence' => '一千人 - Mil personas'],
            ['hanzi' => '万', 'pinyin' => 'wàn', 'meaning' => 'Diez mil', 'level' => 2, 'example_sentence' => '一万块 - Diez mil yuanes'],
            ['hanzi' => '零', 'pinyin' => 'líng', 'meaning' => 'Cero', 'level' => 2, 'example_sentence' => '零分 - Cero puntos'],
            ['hanzi' => '数字', 'pinyin' => 'shùzì', 'meaning' => 'Número', 'level' => 2, 'example_sentence' => '大数字 - Número grande'],
        ];

        foreach ($characters as $char) {
            Character::create(array_merge($char, ['theme_id' => $theme->id]));
        }
    }

    private function createColors(Theme $theme): void
    {
        $characters = [
            ['hanzi' => '红色', 'pinyin' => 'hóng sè', 'meaning' => 'Rojo', 'level' => 1, 'example_sentence' => '红色的苹果 - Manzana roja'],
            ['hanzi' => '蓝色', 'pinyin' => 'lán sè', 'meaning' => 'Azul', 'level' => 1, 'example_sentence' => '蓝色的天空 - Cielo azul'],
            ['hanzi' => '黄色', 'pinyin' => 'huáng sè', 'meaning' => 'Amarillo', 'level' => 1, 'example_sentence' => '黄色的太阳 - Sol amarillo'],
            ['hanzi' => '绿色', 'pinyin' => 'lǜ sè', 'meaning' => 'Verde', 'level' => 1, 'example_sentence' => '绿色的树叶 - Hojas verdes'],
            ['hanzi' => '黑色', 'pinyin' => 'hēi sè', 'meaning' => 'Negro', 'level' => 1, 'example_sentence' => '黑色的夜晚 - Noche negra'],
            ['hanzi' => '白色', 'pinyin' => 'bái sè', 'meaning' => 'Blanco', 'level' => 1, 'example_sentence' => '白色的雪 - Nieve blanca'],
            ['hanzi' => '灰色', 'pinyin' => 'huī sè', 'meaning' => 'Gris', 'level' => 2, 'example_sentence' => '灰色的猫 - Gato gris'],
            ['hanzi' => '紫色', 'pinyin' => 'zǐ sè', 'meaning' => 'Púrpura', 'level' => 2, 'example_sentence' => '紫色的葡萄 - Uva púrpura'],
            ['hanzi' => '橙色', 'pinyin' => 'chéng sè', 'meaning' => 'Naranja', 'level' => 2, 'example_sentence' => '橙色的橙子 - Naranja'],
            ['hanzi' => '粉色', 'pinyin' => 'fěn sè', 'meaning' => 'Rosa', 'level' => 2, 'example_sentence' => '粉色的花 - Flor rosa'],
            ['hanzi' => '棕色', 'pinyin' => 'zōng sè', 'meaning' => 'Marrón', 'level' => 2, 'example_sentence' => '棕色的马 - Caballo marrón'],
            ['hanzi' => '金色', 'pinyin' => 'jīn sè', 'meaning' => 'Dorado', 'level' => 2, 'example_sentence' => '金色的太阳 - Sol dorado'],
            ['hanzi' => '银色', 'pinyin' => 'yín sè', 'meaning' => 'Plateado', 'level' => 2, 'example_sentence' => '银色的月亮 - Luna plateada'],
            ['hanzi' => '彩虹', 'pinyin' => 'cǎihóng', 'meaning' => 'Arco iris', 'level' => 2, 'example_sentence' => '美丽的彩虹 - Hermoso arco iris'],
            ['hanzi' => '透明', 'pinyin' => 'tòumíng', 'meaning' => 'Transparente', 'level' => 3, 'example_sentence' => '透明的玻璃 - Vidrio transparente'],
        ];

        foreach ($characters as $char) {
            Character::create(array_merge($char, ['theme_id' => $theme->id]));
        }
    }
}
