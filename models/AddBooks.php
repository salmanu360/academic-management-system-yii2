<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "add_books".
 *
 * @property integer $id
 * @property string $book_isbn_no
 * @property string $book_no
 * @property string $title
 * @property string $author
 * @property string $edition
 * @property integer $addlibrary_category_id
 * @property string $publisher
 * @property integer $no_of_copies
 * @property integer $remaining_copies 
 * @property string $rack_no
 * @property string $shelf_no
 * @property string $book_position
 * @property integer $book_cost
 * @property integer $language
 * @property string $book_condition
 * @property integer $fk_branch_id
 * @property string $status
 */
class AddBooks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'add_books';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_no', 'title', 'addlibrary_category_id', 'publisher', 'no_of_copies', 'book_cost', 'language', 'book_condition', 'fk_branch_id', 'status'], 'required'],
            [['addlibrary_category_id', 'no_of_copies', 'book_cost', 'fk_branch_id','remaining_copies'], 'integer'],
            [['book_condition', 'status'], 'string'],
            [['book_isbn_no', 'book_no', 'edition', 'rack_no', 'shelf_no', 'book_position','language'], 'string', 'max' => 255],
            [['title', 'author', 'publisher'], 'string', 'max' => 555],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_isbn_no' => 'Book Isbn No',
            'book_no' => 'Book No',
            'title' => 'Title',
            'author' => 'Author',
            'edition' => 'Edition',
            'addlibrary_category_id' => 'Add Category',
            'publisher' => 'Publisher',
            'no_of_copies' => 'No Of Copies',
            'rack_no' => 'Rack No',
            'shelf_no' => 'Shelf No',
            'book_position' => 'Book Position',
            'book_cost' => 'Book Cost',
            'language' => 'Language',
            'book_condition' => 'Book Condition',
            'fk_branch_id' => 'Fk Branch ID',
            'status' => 'Status',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(AddlibraryCategory::className(), ['id' => 'addlibrary_category_id']);
    }
}
